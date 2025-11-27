<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\AffiliatePayout;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AffiliateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $affiliate = auth()->user()->affiliate;
        
        if (!$affiliate) {
            return view('customer.affiliates.apply');
        }

        $affiliate->load(['referrals', 'commissions', 'payouts']);
        
        $stats = [
            'total_referrals' => $affiliate->referrals()->count(),
            'confirmed_referrals' => $affiliate->referrals()->where('status', 'confirmed')->count(),
            'total_commissions' => $affiliate->commissions()->sum('amount'),
            'pending_commissions' => $affiliate->commissions()->where('status', 'pending')->sum('amount'),
            'approved_commissions' => $affiliate->commissions()->where('status', 'approved')->sum('amount'),
            'paid_commissions' => $affiliate->commissions()->where('status', 'paid')->sum('amount'),
        ];

        $recentCommissions = $affiliate->commissions()
            ->with('order')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recentNotifications = auth()->user()->notifications()
            ->whereIn('data->type', ['affiliate_commission_approved', 'affiliate_payout_processed'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get payout history
        $payouts = $affiliate->payouts()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get minimum payout threshold
        $minPayoutThreshold = Setting::getValue('affiliate_min_payout_threshold', 50.00);
        
        // Calculate available for payout (approved commissions that haven't been paid)
        $availableForPayout = $affiliate->commissions()
            ->where('status', 'approved')
            ->sum('amount');

        // Analytics data
        $analytics = $this->getAnalyticsData($affiliate);

        return view('customer.affiliates.dashboard', compact(
            'affiliate', 
            'stats', 
            'recentCommissions', 
            'recentNotifications',
            'payouts',
            'minPayoutThreshold',
            'availableForPayout',
            'analytics'
        ));
    }

    public function apply(Request $request)
    {
        $user = auth()->user();

        if ($user->affiliate) {
            return redirect()->route('customer.affiliates.index')
                ->with('info', 'You already have an affiliate account.');
        }

        // Generate unique affiliate code
        do {
            $code = strtoupper(Str::random(8));
        } while (Affiliate::where('affiliate_code', $code)->exists());

        // Get default commission rate from settings
        $defaultCommissionRate = Setting::getValue('affiliate_default_commission_rate', '10.00');
        $autoApprove = Setting::getValue('affiliate_auto_approve', '0') !== '0';
        
        Affiliate::create([
            'user_id' => $user->id,
            'affiliate_code' => $code,
            'commission_rate' => $defaultCommissionRate,
            'status' => $autoApprove ? 'active' : 'pending',
        ]);

        return redirect()->route('customer.affiliates.index')
            ->with('success', 'Affiliate application submitted. Waiting for approval.');
    }

    public function requestPayout(Request $request): RedirectResponse
    {
        $affiliate = auth()->user()->affiliate;

        if (!$affiliate || $affiliate->status !== 'active') {
            return redirect()->route('customer.affiliates.index')
                ->with('error', 'You must be an active affiliate to request payouts.');
        }

        $minPayoutThreshold = Setting::getValue('affiliate_min_payout_threshold', 50.00);
        
        // Calculate available for payout
        $availableForPayout = $affiliate->commissions()
            ->where('status', 'approved')
            ->sum('amount');

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:' . $availableForPayout],
            'payment_method' => ['required', 'string', 'max:255'],
            'payment_details' => ['nullable', 'string'],
        ]);

        // Check minimum threshold
        if ($validated['amount'] < $minPayoutThreshold) {
            return back()->withErrors([
                'amount' => "Minimum payout amount is " . \App\Services\CurrencyService::format($minPayoutThreshold)
            ]);
        }

        // Check if amount exceeds available
        if ($validated['amount'] > $availableForPayout) {
            return back()->withErrors([
                'amount' => "Requested amount exceeds available balance."
            ]);
        }

        // Create payout request
        $payout = AffiliatePayout::create([
            'affiliate_id' => $affiliate->id,
            'total_amount' => $validated['amount'],
            'status' => 'requested',
            'payment_method' => $validated['payment_method'],
            'payment_details' => $validated['payment_details'] ?? null,
        ]);

        return redirect()->route('customer.affiliates.index')
            ->with('success', 'Payout request submitted successfully. We will process it soon.');
    }

    protected function getAnalyticsData($affiliate): array
    {
        // Earnings over time (last 12 months)
        $earningsOverTime = [];
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $earnings = $affiliate->commissions()
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->where('status', '!=', 'cancelled')
                ->sum('amount');
            
            $earningsOverTime[] = (float) $earnings;
            $months[] = $date->format('M Y');
        }

        // Monthly breakdown (current year)
        $monthlyBreakdown = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthStart = now()->copy()->month($i)->startOfMonth();
            $monthEnd = now()->copy()->month($i)->endOfMonth();
            
            if ($monthStart->isFuture()) {
                break;
            }
            
            $monthlyBreakdown[] = [
                'month' => $monthStart->format('F'),
                'referrals' => $affiliate->referrals()
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->count(),
                'commissions' => (float) $affiliate->commissions()
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->where('status', '!=', 'cancelled')
                    ->sum('amount'),
                'orders' => $affiliate->commissions()
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->where('status', '!=', 'cancelled')
                    ->count(),
            ];
        }

        // Conversion rate
        $totalReferrals = $affiliate->referrals()->count();
        $confirmedReferrals = $affiliate->referrals()->where('status', 'confirmed')->count();
        $conversionRate = $totalReferrals > 0 ? ($confirmedReferrals / $totalReferrals) * 100 : 0;

        // Top performing months (by earnings)
        $topMonths = collect($monthlyBreakdown)
            ->sortByDesc('commissions')
            ->take(3)
            ->values()
            ->all();

        // Average commission per order
        $totalOrders = $affiliate->commissions()->where('status', '!=', 'cancelled')->count();
        $averageCommission = $totalOrders > 0 
            ? (float) ($affiliate->commissions()->where('status', '!=', 'cancelled')->sum('amount') / $totalOrders)
            : 0;

        return [
            'earnings_over_time' => $earningsOverTime,
            'months' => $months,
            'monthly_breakdown' => $monthlyBreakdown,
            'conversion_rate' => round($conversionRate, 2),
            'top_months' => $topMonths,
            'average_commission' => $averageCommission,
        ];
    }
}
