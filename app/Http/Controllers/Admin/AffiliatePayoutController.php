<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\AffiliateCommission;
use App\Models\AffiliatePayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AffiliatePayoutController extends Controller
{
    public function index(): View
    {
        $query = AffiliatePayout::with('affiliate.user');

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('affiliate_id')) {
            $query->where('affiliate_id', request('affiliate_id'));
        }

        $payouts = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.affiliates.payouts.index', compact('payouts'));
    }

    public function create(): View
    {
        $affiliates = Affiliate::active()
            ->where('pending_earnings', '>', 0)
            ->with('user')
            ->orderBy('user_id')
            ->get();
        
        return view('admin.affiliates.payouts.form', compact('affiliates'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'affiliate_id' => ['required', 'exists:affiliates,id'],
            'total_amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string', 'max:255'],
            'payment_details' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $affiliate = Affiliate::findOrFail($data['affiliate_id']);

        if ($data['total_amount'] > $affiliate->pending_earnings) {
            return back()->with('error', 'Payout amount cannot exceed pending earnings.');
        }

        $payout = AffiliatePayout::create([
            'affiliate_id' => $data['affiliate_id'],
            'total_amount' => $data['total_amount'],
            'status' => 'pending',
            'payment_method' => $data['payment_method'],
            'payment_details' => $data['payment_details'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        // Mark commissions as paid
        $commissions = AffiliateCommission::where('affiliate_id', $affiliate->id)
            ->where('status', 'approved')
            ->orderBy('created_at')
            ->get();

        $remaining = $data['total_amount'];
        foreach ($commissions as $commission) {
            if ($remaining <= 0) break;
            
            $amount = min($commission->amount, $remaining);
            $commission->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
            $remaining -= $amount;
        }

        // Update affiliate earnings
        $affiliate->decrement('pending_earnings', $data['total_amount']);
        $affiliate->increment('paid_earnings', $data['total_amount']);

        // Notify affiliate about payout
        if ($affiliate->user) {
            $affiliate->user->notify(new \App\Notifications\AffiliatePayoutProcessedNotification($payout));
        }

        return redirect()->route('admin.affiliates.payouts.index')->with('success', 'Payout created successfully.');
    }

    public function show(AffiliatePayout $payout): View
    {
        $payout->load('affiliate.user');
        return view('admin.affiliates.payouts.show', compact('payout'));
    }

    public function update(Request $request, AffiliatePayout $payout): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,processing,paid,failed'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $oldStatus = $payout->status;
        $payout->update($data);

        if ($data['status'] === 'paid' && !$payout->paid_at) {
            $payout->update(['paid_at' => now()]);
        }

        // Notify affiliate if status changed
        if ($oldStatus !== $payout->status && $payout->affiliate->user) {
            $payout->affiliate->user->notify(new \App\Notifications\AffiliatePayoutProcessedNotification($payout));
        }

        return redirect()->route('admin.affiliates.payouts.show', $payout)
            ->with('success', 'Payout updated successfully.');
    }
}
