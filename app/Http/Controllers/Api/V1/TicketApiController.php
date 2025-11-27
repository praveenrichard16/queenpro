<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Http\Resources\Api\V1\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketApiController extends ApiBaseController
{
    public function index(Request $request): JsonResponse
    {
        if (!$this->hasPermission('read')) {
            return $this->forbiddenResponse('You do not have permission to view tickets');
        }

        $params = $this->getPaginationParams($request);
        $searchParams = $this->getSearchParams($request);

        $query = Ticket::with(['customer', 'assignee', 'category']);

        // Filter by user access unless admin
        $user = auth()->user();
        if ($user && !$user->is_admin && !$user->is_super_admin) {
            $query->where(function ($q) use ($user) {
                $q->where('customer_id', $user->id)
                    ->orWhere('assigned_to', $user->id);
            });
        }

        // Search filter
        if ($searchParams['search']) {
            $query->where(function ($q) use ($searchParams) {
                $q->where('ticket_number', 'like', "%{$searchParams['search']}%")
                    ->orWhere('subject', 'like', "%{$searchParams['search']}%");
            });
        }

        // Status filter
        if ($request->has('status')) {
            $status = $request->get('status');
            if (is_string($status)) {
                $query->where('status', $status);
            }
        }

        // Priority filter
        if ($request->has('priority')) {
            $query->where('priority', $request->get('priority'));
        }

        // Category filter
        if ($request->has('category_id')) {
            $query->where('ticket_category_id', $request->get('category_id'));
        }

        $query->orderBy($searchParams['sort'], $searchParams['order']);

        $tickets = $query->paginate($params['per_page'], ['*'], 'page', $params['page']);

        return $this->paginatedResponse($tickets, TicketResource::class);
    }

    public function show(Ticket $ticket): JsonResponse
    {
        if (!$this->hasPermission('read')) {
            return $this->forbiddenResponse();
        }

        // Check access
        $user = auth()->user();
        if ($user && !$user->is_admin && !$user->is_super_admin) {
            if ($ticket->customer_id !== $user->id && $ticket->assigned_to !== $user->id) {
                return $this->forbiddenResponse('You do not have permission to view this ticket');
            }
        }

        $ticket->load(['customer', 'assignee', 'category', 'messages.user']);

        return $this->successResponse(new TicketResource($ticket), 'Ticket retrieved successfully');
    }

    public function store(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return $this->unauthorizedResponse();
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'ticket_category_id' => 'nullable|exists:ticket_categories,id',
            'priority' => 'nullable|string',
        ]);

        $validated['customer_id'] = $user->id;
        $validated['status'] = 'open';
        $validated['priority'] = $validated['priority'] ?? 'medium';

        $ticket = Ticket::create($validated);
        $ticket->load(['customer', 'category']);

        return $this->successResponse(new TicketResource($ticket), 'Ticket created successfully', 201);
    }

    public function update(Request $request, Ticket $ticket): JsonResponse
    {
        if (!$this->hasPermission('write')) {
            return $this->forbiddenResponse('You do not have permission to update tickets');
        }

        // Check access
        $user = auth()->user();
        if ($user && !$user->is_admin && !$user->is_super_admin) {
            if ($ticket->customer_id !== $user->id && $ticket->assigned_to !== $user->id) {
                return $this->forbiddenResponse('You do not have permission to update this ticket');
            }
        }

        $validated = $request->validate([
            'status' => 'sometimes|required|string',
            'priority' => 'sometimes|required|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $ticket->update($validated);
        $ticket->load(['customer', 'assignee', 'category']);

        return $this->successResponse(new TicketResource($ticket), 'Ticket updated successfully');
    }
}

