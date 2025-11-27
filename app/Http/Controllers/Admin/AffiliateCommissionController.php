<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateCommission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AffiliateCommissionController extends Controller
{
    public function index(): View
    {
        $query = AffiliateCommission::with(['affiliate.user', 'order']);

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('affiliate_id')) {
            $query->where('affiliate_id', request('affiliate_id'));
        }

        $commissions = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.affiliates.commissions.index', compact('commissions'));
    }

    public function approve(AffiliateCommission $commission): RedirectResponse
    {
        if ($commission->status !== 'pending') {
            return back()->with('error', 'Only pending commissions can be approved.');
        }

        $commission->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        if ($commission->referral) {
            $commission->referral->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);
        }

        return back()->with('success', 'Commission approved successfully.');
    }

    public function cancel(AffiliateCommission $commission): RedirectResponse
    {
        if ($commission->status === 'paid') {
            return back()->with('error', 'Cannot cancel paid commissions.');
        }

        $affiliate = $commission->affiliate;
        
        // Revert earnings
        if ($commission->status === 'approved') {
            $affiliate->decrement('pending_earnings', $commission->amount);
        }
        $affiliate->decrement('total_earnings', $commission->amount);

        $commission->update([
            'status' => 'cancelled',
        ]);

        if ($commission->referral) {
            $commission->referral->update([
                'status' => 'cancelled',
            ]);
        }

        return back()->with('success', 'Commission cancelled successfully.');
    }
}
