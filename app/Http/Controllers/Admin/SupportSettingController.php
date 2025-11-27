<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\TicketSla;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportSettingController extends Controller
{
    public function edit(): View
    {
        $settings = [
            'support_email_enabled' => (bool) Setting::getValue('support_email_enabled', true),
            'support_notify_addresses' => Setting::getValue('support_notify_addresses', ''),
            'support_email_customer_updates' => (bool) Setting::getValue('support_email_customer_updates', true),
            'support_default_sla_id' => Setting::getValue('support_default_sla_id'),
        ];

        $availableSlas = TicketSla::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.tickets.settings', [
            'settings' => $settings,
            'availableSlas' => $availableSlas,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'support_email_enabled' => ['nullable', 'boolean'],
            'support_notify_addresses' => ['nullable', 'string', 'max:1024'],
            'support_email_customer_updates' => ['nullable', 'boolean'],
            'support_default_sla_id' => ['nullable', 'exists:ticket_slas,id'],
        ]);

        Setting::setValue('support_email_enabled', $request->boolean('support_email_enabled'));
        Setting::setValue('support_notify_addresses', $validated['support_notify_addresses'] ?? null);
        Setting::setValue('support_email_customer_updates', $request->boolean('support_email_customer_updates'));
        Setting::setValue('support_default_sla_id', $validated['support_default_sla_id'] ?? null);

        return back()->with('success', 'Support notification settings updated.');
    }
}

