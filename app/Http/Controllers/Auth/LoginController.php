<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
	public function showLoginForm(Request $request)
	{
		// Pass redirect URL to view if provided
		$redirect = $request->input('redirect');
		return view('auth.login', compact('redirect'));
	}

	public function login(Request $request)
	{
		$credentials = $request->validate([
			'email' => ['required', 'email'],
			'password' => ['required'],
		]);

		if (Auth::attempt($credentials, $request->boolean('remember'))) {
			$request->session()->regenerate();
			$user = Auth::user();
			
			// Log login activity
			ActivityLog::create([
				'user_id' => $user->id,
				'action_type' => 'login',
				'description' => 'User logged in',
				'ip_address' => $request->ip(),
				'user_agent' => $request->userAgent(),
				'route' => 'login',
				'method' => 'POST',
			]);
			
			// Check for WhatsApp checkout product
			if (Session::has('whatsapp_checkout_product')) {
				$whatsappProduct = Session::get('whatsapp_checkout_product');
				return redirect()->route('whatsapp.checkout.initiate', [
					'product_id' => $whatsappProduct['product_id'],
					'quantity' => $whatsappProduct['quantity'] ?? 1,
				]);
			}
			
			// Check for redirect parameter (e.g., from cart page)
			if ($request->has('redirect')) {
				return redirect($request->input('redirect'));
			}
			
			return redirect()->intended($user->is_admin ? route('admin.dashboard') : route('customer.dashboard'));
		}

		return back()->withErrors([
			'email' => 'The provided credentials do not match our records.',
		]);
	}

	public function logout(Request $request)
	{
		$user = Auth::user();
		
		// Log logout activity before logging out
		if ($user) {
			ActivityLog::create([
				'user_id' => $user->id,
				'action_type' => 'logout',
				'description' => 'User logged out',
				'ip_address' => $request->ip(),
				'user_agent' => $request->userAgent(),
				'route' => 'logout',
				'method' => 'POST',
			]);
		}
		
		Auth::logout();
		$request->session()->invalidate();
		$request->session()->regenerateToken();
		return redirect()->route('home');
	}
}
