<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AffiliateController extends Controller
{
    public function index(): View
    {
        $query = Affiliate::with('user')->withCount(['referrals', 'commissions']);

        if (request('search')) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . request('search') . '%')
                  ->orWhere('email', 'like', '%' . request('search') . '%');
            })->orWhere('affiliate_code', 'like', '%' . request('search') . '%');
        }

        if (request('status') !== null) {
            $query->where('status', request('status'));
        }

        $affiliates = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.affiliates.index', compact('affiliates'));
    }

    public function create(): View
    {
        $users = User::whereDoesntHave('affiliate')->orderBy('name')->get();
        $affiliate = new Affiliate();
        return view('admin.affiliates.form', compact('affiliate', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id', 'unique:affiliates,user_id'],
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', 'in:pending,active,suspended'],
            'payment_info' => ['nullable', 'array'],
            'notes' => ['nullable', 'string'],
        ]);

        // Generate unique affiliate code
        do {
            $code = strtoupper(Str::random(8));
        } while (Affiliate::where('affiliate_code', $code)->exists());

        Affiliate::create([
            'user_id' => $data['user_id'],
            'affiliate_code' => $code,
            'commission_rate' => $data['commission_rate'],
            'status' => $data['status'],
            'payment_info' => $data['payment_info'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('admin.affiliates.index')->with('success', 'Affiliate created successfully.');
    }

    public function edit(Affiliate $affiliate): View
    {
        $users = User::where('id', $affiliate->user_id)
            ->orWhereDoesntHave('affiliate')
            ->orderBy('name')
            ->get();
        return view('admin.affiliates.form', compact('affiliate', 'users'));
    }

    public function update(Request $request, Affiliate $affiliate): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id', 'unique:affiliates,user_id,' . $affiliate->id],
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', 'in:pending,active,suspended'],
            'payment_info' => ['nullable', 'array'],
            'notes' => ['nullable', 'string'],
        ]);

        $affiliate->update($data);

        return redirect()->route('admin.affiliates.index')->with('success', 'Affiliate updated successfully.');
    }

    public function destroy(Affiliate $affiliate): RedirectResponse
    {
        if ($affiliate->commissions()->where('status', '!=', 'cancelled')->exists()) {
            return back()->with('error', 'Cannot delete affiliate with active commissions.');
        }

        $affiliate->delete();

        return redirect()->route('admin.affiliates.index')->with('success', 'Affiliate deleted successfully.');
    }
}
