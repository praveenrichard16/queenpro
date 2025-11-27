<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AffiliateSettingController extends Controller
{
    public function edit(): View
    {
        $settings = [
            'affiliate_enabled' => Setting::getValue('affiliate_enabled', '1') !== '0',
            'affiliate_cookie_period' => Setting::getValue('affiliate_cookie_period', 30),
            'affiliate_default_commission_rate' => Setting::getValue('affiliate_default_commission_rate', '10.00'),
            'affiliate_min_payout_threshold' => Setting::getValue('affiliate_min_payout_threshold', '50.00'),
            'affiliate_payout_processing_fee' => Setting::getValue('affiliate_payout_processing_fee', '0.00'),
            'affiliate_auto_approve' => Setting::getValue('affiliate_auto_approve', '0') !== '0',
            'affiliate_program_description' => Setting::getValue('affiliate_program_description', ''),
        ];

        return view('admin.affiliates.settings', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'affiliate_enabled' => ['nullable', 'boolean'],
            'affiliate_cookie_period' => ['required', 'integer', 'min:1', 'max:365'],
            'affiliate_default_commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'affiliate_min_payout_threshold' => ['required', 'numeric', 'min:0'],
            'affiliate_payout_processing_fee' => ['nullable', 'numeric', 'min:0'],
            'affiliate_auto_approve' => ['nullable', 'boolean'],
            'affiliate_program_description' => ['nullable', 'string', 'max:5000'],
        ]);

        Setting::setValue('affiliate_enabled', $request->boolean('affiliate_enabled') ? '1' : '0');
        Setting::setValue('affiliate_cookie_period', $validated['affiliate_cookie_period'], 'string');
        Setting::setValue('affiliate_default_commission_rate', $validated['affiliate_default_commission_rate'], 'string');
        Setting::setValue('affiliate_min_payout_threshold', $validated['affiliate_min_payout_threshold'], 'string');
        Setting::setValue('affiliate_payout_processing_fee', $validated['affiliate_payout_processing_fee'] ?? '0.00', 'string');
        Setting::setValue('affiliate_auto_approve', $request->boolean('affiliate_auto_approve') ? '1' : '0');
        Setting::setValue('affiliate_program_description', $validated['affiliate_program_description'] ?? '', 'string');

        return back()->with('success', 'Affiliate settings updated successfully.');
    }
}

