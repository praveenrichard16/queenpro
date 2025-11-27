<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'marketing_opt_in' => ['nullable', 'boolean'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'marketing_opt_in' => $request->boolean('marketing_opt_in', true),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect to email verification notice if email verification is required
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('customer.dashboard'))->with('success', 'Welcome! Your account has been created.');
        } else {
            return redirect()->route('verification.notice')->with('success', 'Registration successful! Please verify your email address.');
        }
    }
}

