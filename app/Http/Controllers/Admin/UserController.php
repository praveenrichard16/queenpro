<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $activeTab = $request->get('tab', 'admins');
        $search = $request->string('search')->toString();
        
        // Load data based on active tab
        if ($activeTab === 'admins') {
            $query = User::where('is_admin', true)->orWhere('is_super_admin', true)->latest();
            if ($search !== '') {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            }
            $users = $query->withCount('activityLogs')->paginate(20)->withQueryString();
        } elseif ($activeTab === 'staff') {
            $query = User::where('is_staff', true)->where('is_admin', false)->latest();
            if ($search !== '') {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            }
            $users = $query->with(['modules'])->withCount('activityLogs')->paginate(20)->withQueryString();
        } else {
            $query = User::where('is_admin', false)->where('is_staff', false)->latest();
            if ($search !== '') {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            }
            $users = $query->withCount(['orders', 'activityLogs'])->paginate(20)->withQueryString();
        }
        
        return view('admin.users.index', [
            'activeTab' => $activeTab,
            'users' => $users,
            'search' => $search,
        ]);
    }

    public function admins(Request $request): View
    {
        $query = User::where('is_admin', true)->orWhere('is_super_admin', true)->latest();

        $search = $request->string('search')->toString();
        if ($search !== '') {
            $query->where(function ($innerQuery) use ($search) {
                $innerQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->withCount('activityLogs')->paginate(20)->withQueryString();

        return view('admin.users.admins', [
            'users' => $users,
            'search' => $search,
        ]);
    }

    public function staff(Request $request): View
    {
        $query = User::where('is_staff', true)->where('is_admin', false)->latest();

        $search = $request->string('search')->toString();
        if ($search !== '') {
            $query->where(function ($innerQuery) use ($search) {
                $innerQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->with('modules')->withCount('activityLogs')->paginate(20)->withQueryString();

        return view('admin.users.staff', [
            'users' => $users,
            'search' => $search,
        ]);
    }

    public function customers(Request $request): View
    {
        $query = User::where('is_admin', false)->where('is_staff', false)->latest();

        $search = $request->string('search')->toString();
        if ($search !== '') {
            $query->where(function ($innerQuery) use ($search) {
                $innerQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->withCount(['orders', 'activityLogs'])->paginate(20)->withQueryString();

        return view('admin.users.customers', [
            'users' => $users,
            'search' => $search,
        ]);
    }

    public function adminActivity(User $user): View
    {
        $activities = $user->activityLogs()->latest()->paginate(20);
        
        return view('admin.users.admin-activity', [
            'user' => $user,
            'activities' => $activities,
        ]);
    }

    public function staffActivity(User $user): View
    {
        $activities = $user->activityLogs()->latest()->paginate(20);
        
        return view('admin.users.staff-activity', [
            'user' => $user,
            'activities' => $activities,
        ]);
    }

    public function customerActivity(User $user): View
    {
        $activities = $user->activityLogs()->latest()->paginate(20);
        $journeyEvents = $user->journeyEvents()->latest()->paginate(20, ['*'], 'journey_page');
        
        return view('admin.users.customer-activity', [
            'user' => $user,
            'activities' => $activities,
            'journeyEvents' => $journeyEvents,
        ]);
    }

    public function customerDetails(User $user): View
    {
        $user->load(['orders', 'addresses', 'supportTickets', 'activityLogs', 'journeyEvents']);
        
        return view('admin.users.customer-details', [
            'user' => $user,
        ]);
    }

    public function updateModules(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'modules' => ['nullable', 'array'],
            'modules.*' => ['string', 'in:' . implode(',', array_keys(config('modules.modules')))],
        ]);

        $user->modules()->delete();
        
        if ($request->has('modules')) {
            foreach ($request->modules as $moduleName) {
                $user->modules()->create(['module_name' => $moduleName]);
            }
        }

        // Redirect to assign-modules page if coming from there, otherwise back
        if ($request->has('from_assign_page')) {
            return redirect()->route('admin.users.assign-modules')->with('success', 'Module permissions updated successfully.');
        }

        return redirect()->back()->with('success', 'Module permissions updated successfully.');
    }

    public function assignModules(Request $request): View
    {
        $query = User::where('is_staff', true)->where('is_admin', false)->with('modules')->orderBy('name');

        $search = $request->string('search')->toString();
        if ($search !== '') {
            $query->where(function ($innerQuery) use ($search) {
                $innerQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $staff = $query->paginate(20)->withQueryString();

        return view('admin.users.assign-modules', [
            'staff' => $staff,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.users.form', [
            'user' => new User(),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $isSuperAdmin = (bool) $request->user()?->is_super_admin;

        $role = $isSuperAdmin ? $request->input('role', 'customer') : 'customer';
        $data['is_admin'] = $isSuperAdmin && $role === 'admin';
        $data['is_staff'] = $isSuperAdmin && $role === 'staff';

        if (!$isSuperAdmin) {
            $data['designation'] = null;
        }

        if ($request->hasFile('avatar')) {
            $data['avatar_path'] = $request->file('avatar')->store('users/avatars', 'public');
        }

        $data['password'] = Hash::make($data['password']);
        $data['is_super_admin'] = false;

        $user = User::create($data);

        // Handle module assignments for staff
        if ($data['is_staff'] && $request->has('modules')) {
            foreach ($request->modules as $moduleName) {
                $user->modules()->create(['module_name' => $moduleName]);
            }
        }

        return redirect()
            ->route('admin.users.index', ['tab' => $role === 'admin' ? 'admins' : ($role === 'staff' ? 'staff' : 'customers')])
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $user->load('modules');
        return view('admin.users.form', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        $isSuperAdmin = (bool) $request->user()?->is_super_admin;

        if ($isSuperAdmin) {
            $role = $request->input('role', 'customer');
            $data['is_admin'] = $role === 'admin';
            $data['is_staff'] = $role === 'staff';
        } else {
            $data['is_admin'] = $user->is_admin;
            $data['is_staff'] = $user->is_staff;
            $data['designation'] = $user->designation;
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $data['avatar_path'] = $request->file('avatar')->store('users/avatars', 'public');
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password'], $data['password_confirmation']);
        }

        $user->update($data);

        // Handle module assignments for staff (only if user is staff and not admin)
        if ($isSuperAdmin) {
            if ($user->is_staff && !$user->is_admin) {
                $user->modules()->delete();
                if ($request->has('modules')) {
                    foreach ($request->modules as $moduleName) {
                        $user->modules()->create(['module_name' => $moduleName]);
                    }
                }
            } else {
                // If user is no longer staff or is now admin, remove module assignments
                $user->modules()->delete();
            }
        }

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}

