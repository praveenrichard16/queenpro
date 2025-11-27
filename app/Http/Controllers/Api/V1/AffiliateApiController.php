<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Models\Affiliate;
use App\Models\AffiliateCommission;
use App\Models\AffiliatePayout;
use App\Models\AffiliateReferral;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AffiliateApiController extends ApiBaseController
{
    public function index(Request $request): JsonResponse
    {
        if (!$this->hasPermission('read')) {
            return $this->forbiddenResponse('You do not have permission to view affiliates');
        }

        $params = $this->getPaginationParams($request);
        $searchParams = $this->getSearchParams($request);

        $query = Affiliate::with(['user']);

        // Filter by user access unless admin
        $user = auth()->user();
        if ($user && !$user->is_admin && !$user->is_super_admin) {
            $query->where('user_id', $user->id);
        }

        // Search filter
        if ($searchParams['search']) {
            $query->whereHas('user', function ($q) use ($searchParams) {
                $q->where('name', 'like', "%{$searchParams['search']}%")
                    ->orWhere('email', 'like', "%{$searchParams['search']}%");
            });
        }

        $query->orderBy($searchParams['sort'], $searchParams['order']);

        $affiliates = $query->paginate($params['per_page'], ['*'], 'page', $params['page']);

        return $this->paginatedResponse($affiliates->map(function ($affiliate) {
            return [
                'id' => $affiliate->id,
                'user_id' => $affiliate->user_id,
                'affiliate_code' => $affiliate->affiliate_code,
                'referral_code' => $affiliate->affiliate_code, // Alias for backward compatibility
                'status' => $affiliate->status,
                'user' => $affiliate->user ? [
                    'id' => $affiliate->user->id,
                    'name' => $affiliate->user->name,
                    'email' => $affiliate->user->email,
                ] : null,
                'created_at' => $affiliate->created_at?->toISOString(),
            ];
        }), null);
    }

    public function show(Affiliate $affiliate): JsonResponse
    {
        if (!$this->hasPermission('read')) {
            return $this->forbiddenResponse();
        }

        // Check access
        $user = auth()->user();
        if ($user && !$user->is_admin && !$user->is_super_admin) {
            if ($affiliate->user_id !== $user->id) {
                return $this->forbiddenResponse('You do not have permission to view this affiliate');
            }
        }

        $affiliate->load(['user']);

        return $this->successResponse([
            'id' => $affiliate->id,
            'user_id' => $affiliate->user_id,
            'affiliate_code' => $affiliate->affiliate_code,
            'referral_code' => $affiliate->affiliate_code, // Alias for backward compatibility
            'status' => $affiliate->status,
            'user' => $affiliate->user ? new \App\Http\Resources\Api\V1\UserResource($affiliate->user) : null,
            'created_at' => $affiliate->created_at?->toISOString(),
        ], 'Affiliate retrieved successfully');
    }

    public function me(): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return $this->unauthorizedResponse();
        }

        $affiliate = Affiliate::where('user_id', $user->id)->first();

        if (!$affiliate) {
            return $this->notFoundResponse('Affiliate');
        }

        $affiliate->load(['user']);

        return $this->successResponse([
            'id' => $affiliate->id,
            'affiliate_code' => $affiliate->affiliate_code,
            'referral_code' => $affiliate->affiliate_code, // Alias for backward compatibility
            'status' => $affiliate->status,
            'referral_link' => $affiliate->referral_url,
            'created_at' => $affiliate->created_at?->toISOString(),
        ], 'Affiliate retrieved successfully');
    }

    public function stats(): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return $this->unauthorizedResponse();
        }

        $affiliate = Affiliate::where('user_id', $user->id)->first();

        if (!$affiliate) {
            return $this->notFoundResponse('Affiliate');
        }

        $stats = [
            'total_referrals' => $affiliate->referrals()->count(),
            'confirmed_referrals' => $affiliate->referrals()->where('status', 'confirmed')->count(),
            'total_earnings' => (float) $affiliate->commissions()->sum('amount'),
            'pending_earnings' => (float) $affiliate->commissions()->where('status', 'pending')->sum('amount'),
            'approved_earnings' => (float) $affiliate->commissions()->where('status', 'approved')->sum('amount'),
            'paid_earnings' => (float) $affiliate->commissions()->where('status', 'paid')->sum('amount'),
            'total_earnings_formatted' => \App\Services\CurrencyService::format($affiliate->commissions()->sum('amount')),
            'pending_earnings_formatted' => \App\Services\CurrencyService::format($affiliate->commissions()->where('status', 'pending')->sum('amount')),
            'approved_earnings_formatted' => \App\Services\CurrencyService::format($affiliate->commissions()->where('status', 'approved')->sum('amount')),
            'paid_earnings_formatted' => \App\Services\CurrencyService::format($affiliate->commissions()->where('status', 'paid')->sum('amount')),
            'commission_rate' => (float) $affiliate->commission_rate,
            'affiliate_code' => $affiliate->affiliate_code,
            'status' => $affiliate->status,
        ];

        return $this->successResponse($stats, 'Affiliate statistics retrieved successfully');
    }

    public function commissions(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return $this->unauthorizedResponse();
        }

        $affiliate = Affiliate::where('user_id', $user->id)->first();

        if (!$affiliate) {
            return $this->notFoundResponse('Affiliate');
        }

        $params = $this->getPaginationParams($request);
        
        $query = AffiliateCommission::where('affiliate_id', $affiliate->id)
            ->with(['order']);

        // Status filter
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        $commissions = $query->orderBy('created_at', 'desc')
            ->paginate($params['per_page'], ['*'], 'page', $params['page']);

        return $this->paginatedResponse($commissions->map(function ($commission) {
            return [
                'id' => $commission->id,
                'order_id' => $commission->order_id,
                'order_number' => $commission->order?->order_number,
                'amount' => (float) $commission->amount,
                'amount_formatted' => \App\Services\CurrencyService::format($commission->amount),
                'status' => $commission->status,
                'created_at' => $commission->created_at?->toISOString(),
                'approved_at' => $commission->approved_at?->toISOString(),
                'paid_at' => $commission->paid_at?->toISOString(),
            ];
        }), null);
    }

    public function referrals(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return $this->unauthorizedResponse();
        }

        $affiliate = Affiliate::where('user_id', $user->id)->first();

        if (!$affiliate) {
            return $this->notFoundResponse('Affiliate');
        }

        $params = $this->getPaginationParams($request);
        
        $query = AffiliateReferral::where('affiliate_id', $affiliate->id)
            ->with(['order']);

        // Status filter
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        $referrals = $query->orderBy('created_at', 'desc')
            ->paginate($params['per_page'], ['*'], 'page', $params['page']);

        return $this->paginatedResponse($referrals->map(function ($referral) {
            return [
                'id' => $referral->id,
                'order_id' => $referral->order_id,
                'order_number' => $referral->order?->order_number,
                'customer_email' => $referral->customer_email,
                'referral_code' => $referral->referral_code,
                'commission_amount' => (float) $referral->commission_amount,
                'commission_amount_formatted' => \App\Services\CurrencyService::format($referral->commission_amount),
                'status' => $referral->status,
                'created_at' => $referral->created_at?->toISOString(),
                'confirmed_at' => $referral->confirmed_at?->toISOString(),
            ];
        }), null);
    }

    public function payoutRequest(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return $this->unauthorizedResponse();
        }

        $affiliate = Affiliate::where('user_id', $user->id)->first();

        if (!$affiliate || $affiliate->status !== 'active') {
            return $this->errorResponse('You must be an active affiliate to request payouts.', 403);
        }

        $minPayoutThreshold = Setting::getValue('affiliate_min_payout_threshold', 50.00);
        
        $availableForPayout = $affiliate->commissions()
            ->where('status', 'approved')
            ->sum('amount');

        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'numeric', 'min:0.01', 'max:' . $availableForPayout],
            'payment_method' => ['required', 'string', 'max:255'],
            'payment_details' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $data = $validator->validated();

        // Check minimum threshold
        if ($data['amount'] < $minPayoutThreshold) {
            return $this->errorResponse("Minimum payout amount is " . \App\Services\CurrencyService::format($minPayoutThreshold), 422);
        }

        // Check if amount exceeds available
        if ($data['amount'] > $availableForPayout) {
            return $this->errorResponse("Requested amount exceeds available balance.", 422);
        }

        // Create payout request
        $payout = AffiliatePayout::create([
            'affiliate_id' => $affiliate->id,
            'total_amount' => $data['amount'],
            'status' => 'requested',
            'payment_method' => $data['payment_method'],
            'payment_details' => $data['payment_details'] ?? null,
        ]);

        return $this->successResponse([
            'id' => $payout->id,
            'total_amount' => (float) $payout->total_amount,
            'total_amount_formatted' => \App\Services\CurrencyService::format($payout->total_amount),
            'status' => $payout->status,
            'payment_method' => $payout->payment_method,
            'created_at' => $payout->created_at?->toISOString(),
        ], 'Payout request submitted successfully', 201);
    }

    public function payouts(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return $this->unauthorizedResponse();
        }

        $affiliate = Affiliate::where('user_id', $user->id)->first();

        if (!$affiliate) {
            return $this->notFoundResponse('Affiliate');
        }

        $params = $this->getPaginationParams($request);
        
        $query = AffiliatePayout::where('affiliate_id', $affiliate->id);

        // Status filter
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        $payouts = $query->orderBy('created_at', 'desc')
            ->paginate($params['per_page'], ['*'], 'page', $params['page']);

        return $this->paginatedResponse($payouts->map(function ($payout) {
            return [
                'id' => $payout->id,
                'total_amount' => (float) $payout->total_amount,
                'total_amount_formatted' => \App\Services\CurrencyService::format($payout->total_amount),
                'status' => $payout->status,
                'payment_method' => $payout->payment_method,
                'transaction_id' => $payout->transaction_id,
                'payment_details' => $payout->payment_details,
                'notes' => $payout->notes,
                'created_at' => $payout->created_at?->toISOString(),
                'paid_at' => $payout->paid_at?->toISOString(),
            ];
        }), null);
    }

    public function link(): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return $this->unauthorizedResponse();
        }

        $affiliate = Affiliate::where('user_id', $user->id)->first();

        if (!$affiliate) {
            return $this->notFoundResponse('Affiliate');
        }

        return $this->successResponse([
            'referral_link' => $affiliate->referral_url,
            'affiliate_code' => $affiliate->affiliate_code,
        ], 'Referral link retrieved successfully');
    }
}

