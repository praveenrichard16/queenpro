<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = CustomerAddress::query()
            ->where('user_id', $request->user()->id)
            ->orderByDesc('is_default')
            ->orderBy('created_at')
            ->get();

        return view('customer.addresses.index', [
            'addresses' => $addresses,
        ]);
    }

    public function create()
    {
        return view('customer.addresses.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:shipping,billing'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:32'],
            'street' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:32'],
            'country' => ['required', 'string', 'max:255'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $address = $request->user()->addresses()->create($data);

        if ($request->boolean('is_default')) {
            $request->user()->addresses()
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        return redirect()->route('customer.addresses.index')->with('success', 'Address saved successfully.');
    }

    public function edit(Request $request, CustomerAddress $address)
    {
        abort_unless($address->user_id === $request->user()->id, 404);

        return view('customer.addresses.edit', [
            'address' => $address,
        ]);
    }

    public function update(Request $request, CustomerAddress $address)
    {
        abort_unless($address->user_id === $request->user()->id, 404);

        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:shipping,billing'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:32'],
            'street' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:32'],
            'country' => ['required', 'string', 'max:255'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $address->update($data);

        if ($request->boolean('is_default')) {
            $request->user()->addresses()
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        return redirect()->route('customer.addresses.index')->with('success', 'Address updated successfully.');
    }

    public function destroy(Request $request, CustomerAddress $address)
    {
        abort_unless($address->user_id === $request->user()->id, 404);

        $address->delete();

        return redirect()->route('customer.addresses.index')->with('success', 'Address removed.');
    }

    public function setDefault(Request $request, CustomerAddress $address)
    {
        abort_unless($address->user_id === $request->user()->id, 404);

        $request->user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return redirect()->route('customer.addresses.index')->with('success', 'Default address updated.');
    }
}

