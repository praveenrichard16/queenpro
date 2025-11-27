<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiUsageLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laravel\Sanctum\PersonalAccessToken;

class ApiTokenController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        
        $query = PersonalAccessToken::with('tokenable')
            ->where('tokenable_type', User::class)
            ->latest();
        
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('tokenable', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }
        
        $tokens = $query->paginate(20)->withQueryString();
        
        // Get statistics
        $totalTokens = PersonalAccessToken::where('tokenable_type', User::class)->count();
        
        // Check if api_usage_logs table exists
        $tableExists = \Illuminate\Support\Facades\Schema::hasTable('api_usage_logs');
        
        $activeTokens = 0;
        if ($tableExists) {
            $activeTokens = PersonalAccessToken::where('tokenable_type', User::class)
                ->whereIn('id', ApiUsageLog::distinct('token_id')->whereNotNull('token_id')->pluck('token_id'))
                ->count();
        }
        
        // Get last used timestamp for each token
        if ($tableExists) {
            foreach ($tokens as $token) {
                $lastUsage = ApiUsageLog::where('token_id', $token->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                $token->last_used_at = $lastUsage?->created_at;
            }
        } else {
            foreach ($tokens as $token) {
                $token->last_used_at = null;
            }
        }
        
        return view('admin.api-tokens.index', [
            'tokens' => $tokens,
            'search' => $search,
            'totalTokens' => $totalTokens,
            'activeTokens' => $activeTokens,
        ]);
    }

    public function create(): View
    {
        // Get all users (admins, staff, and customers can have API tokens)
        $users = User::orderBy('name')->get();
        
        return view('admin.api-tokens.form', [
            'token' => null,
            'users' => $users,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'user_id' => ['required', 'exists:users,id'],
            'abilities' => ['nullable', 'array'],
            'abilities.*' => ['string', 'in:read,write,delete,admin'],
        ]);

        $user = User::findOrFail($data['user_id']);
        
        // If no abilities selected or empty array, grant all permissions
        $abilities = !empty($data['abilities']) ? $data['abilities'] : ['*'];
        
        // Create token with abilities
        $token = $user->createToken($data['name'], $abilities);
        
        return redirect()
            ->route('admin.api-tokens.index')
            ->with('success', 'API token created successfully. Please copy the token now - it will not be shown again.')
            ->with('token_plain', $token->plainTextToken)
            ->with('token_name', $data['name']);
    }

    public function edit(PersonalAccessToken $api_token): View
    {
        // Ensure the token belongs to a User model
        if ($api_token->tokenable_type !== User::class) {
            abort(404);
        }
        
        $api_token->load('tokenable');
        
        // Get all users (for display purposes, though we won't allow changing user)
        $users = User::orderBy('name')->get();
        
        return view('admin.api-tokens.form', [
            'token' => $api_token,
            'users' => $users,
        ]);
    }

    public function update(Request $request, PersonalAccessToken $api_token): RedirectResponse
    {
        // Ensure the token belongs to a User model
        if ($api_token->tokenable_type !== User::class) {
            abort(404);
        }
        
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'abilities' => ['nullable', 'array'],
            'abilities.*' => ['string', 'in:read,write,delete,admin'],
        ]);

        $api_token->name = $data['name'];
        
        // If no abilities selected or empty array, grant all permissions
        $api_token->abilities = !empty($data['abilities']) ? $data['abilities'] : ['*'];
        
        $api_token->save();

        return redirect()
            ->route('admin.api-tokens.index')
            ->with('success', 'API token updated successfully.');
    }

    public function destroy(PersonalAccessToken $api_token): RedirectResponse
    {
        // Ensure the token belongs to a User model
        if ($api_token->tokenable_type !== User::class) {
            abort(404);
        }
        
        $api_token->delete();

        return redirect()
            ->route('admin.api-tokens.index')
            ->with('success', 'API token deleted successfully.');
    }
}

