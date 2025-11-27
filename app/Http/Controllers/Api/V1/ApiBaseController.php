<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

class ApiBaseController extends Controller
{
    /**
     * Success response helper
     */
    protected function successResponse($data = null, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Error response helper
     */
    protected function errorResponse(string $message = 'Error', int $statusCode = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Validation error response
     */
    protected function validationErrorResponse($validator): JsonResponse
    {
        return $this->errorResponse(
            'Validation failed',
            422,
            $validator->errors()
        );
    }

    /**
     * Paginated response helper
     */
    protected function paginatedResponse(LengthAwarePaginator $paginator, $resourceClass = null): JsonResponse
    {
        $data = [
            'data' => $resourceClass ? $resourceClass::collection($paginator->items()) : $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ];

        return $this->successResponse($data, 'Data retrieved successfully');
    }

    /**
     * Check if user has permission
     */
    protected function hasPermission(string $permission): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        $token = $user->currentAccessToken();
        
        if (!$token) {
            return false;
        }

        // Admin users have all permissions
        if ($user->is_admin || $user->is_super_admin) {
            return true;
        }

        // Check token abilities
        $abilities = $token->abilities ?? [];
        
        if (in_array('*', $abilities)) {
            return true;
        }

        return in_array($permission, $abilities);
    }

    /**
     * Check if user can access resource
     */
    protected function canAccessResource($resource, string $userIdField = 'user_id'): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        // Admin users can access all resources
        if ($user->is_admin || $user->is_super_admin) {
            return true;
        }

        // Check if resource belongs to user
        if (isset($resource->$userIdField)) {
            return $resource->$userIdField === $user->id;
        }

        return false;
    }

    /**
     * Filter query based on user permissions
     */
    protected function filterByUserAccess($query, string $userIdField = 'user_id')
    {
        $user = auth()->user();
        
        if (!$user) {
            return $query->whereRaw('1 = 0'); // No results for unauthenticated
        }

        // Admin users can see all
        if ($user->is_admin || $user->is_super_admin) {
            return $query;
        }

        // Regular users can only see their own resources
        return $query->where($userIdField, $user->id);
    }

    /**
     * Get pagination parameters from request
     */
    protected function getPaginationParams(Request $request): array
    {
        return [
            'per_page' => (int) $request->get('per_page', 15),
            'page' => (int) $request->get('page', 1),
        ];
    }

    /**
     * Get search and filter parameters
     */
    protected function getSearchParams(Request $request): array
    {
        return [
            'search' => $request->get('search', ''),
            'sort' => $request->get('sort', 'created_at'),
            'order' => $request->get('order', 'desc'),
        ];
    }

    /**
     * Not found response
     */
    protected function notFoundResponse(string $resource = 'Resource'): JsonResponse
    {
        return $this->errorResponse("{$resource} not found", 404);
    }

    /**
     * Unauthorized response
     */
    protected function unauthorizedResponse(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->errorResponse($message, 401);
    }

    /**
     * Forbidden response
     */
    protected function forbiddenResponse(string $message = 'Forbidden'): JsonResponse
    {
        return $this->errorResponse($message, 403);
    }
}

