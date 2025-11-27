<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserApiController extends ApiBaseController
{
    public function index(Request $request): JsonResponse
    {
        if (!$this->hasPermission('read')) {
            return $this->forbiddenResponse('You do not have permission to view users');
        }

        $params = $this->getPaginationParams($request);
        $searchParams = $this->getSearchParams($request);

        $query = User::query();

        // Admin can see all, others see only themselves
        $user = auth()->user();
        if (!$user || (!$user->is_admin && !$user->is_super_admin)) {
            $query->where('id', $user->id);
        }

        // Search filter
        if ($searchParams['search']) {
            $query->where(function ($q) use ($searchParams) {
                $q->where('name', 'like', "%{$searchParams['search']}%")
                    ->orWhere('email', 'like', "%{$searchParams['search']}%");
            });
        }

        // Type filter
        if ($request->has('type')) {
            $type = $request->get('type');
            if ($type === 'admin') {
                $query->where(function ($q) {
                    $q->where('is_admin', true)->orWhere('is_super_admin', true);
                });
            } elseif ($type === 'staff') {
                $query->where('is_staff', true)->where('is_admin', false);
            } elseif ($type === 'customer') {
                $query->where('is_admin', false)->where('is_staff', false);
            }
        }

        $query->orderBy($searchParams['sort'], $searchParams['order']);

        $users = $query->paginate($params['per_page'], ['*'], 'page', $params['page']);

        return $this->paginatedResponse($users, UserResource::class);
    }

    public function show(User $user): JsonResponse
    {
        $currentUser = auth()->user();

        // Users can only see themselves unless admin
        if (!$currentUser || ($currentUser->id !== $user->id && !$currentUser->is_admin && !$currentUser->is_super_admin)) {
            return $this->forbiddenResponse('You do not have permission to view this user');
        }

        return $this->successResponse(new UserResource($user), 'User retrieved successfully');
    }

    public function me(): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return $this->unauthorizedResponse();
        }

        return $this->successResponse(new UserResource($user), 'Current user retrieved successfully');
    }
}

