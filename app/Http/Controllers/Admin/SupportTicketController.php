<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'status' => ['nullable', 'string'],
            'priority' => ['nullable', 'string'],
            'assignee' => ['nullable', 'integer'],
            'search' => ['nullable', 'string'],
        ]);

        $query = Ticket::query()
            ->with(['customer:id,name,email', 'assignee:id,name', 'category:id,name'])
            ->latest();

        if (!empty($filters['status']) && in_array($filters['status'], TicketStatus::values(), true)) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority']) && in_array($filters['priority'], TicketPriority::values(), true)) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['assignee'])) {
            $query->where('assigned_to', $filters['assignee']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($builder) use ($search): void {
                $builder->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $tickets = $query->paginate(15)->withQueryString();

        $statusCounts = $this->statusCounts();

        $assignees = Ticket::query()
            ->select('assigned_to')
            ->whereNotNull('assigned_to')
            ->with('assignee:id,name')
            ->get()
            ->pluck('assignee')
            ->filter()
            ->unique('id')
            ->values();

        return view('admin.tickets.index', [
            'tickets' => $tickets,
            'filters' => $filters,
            'statusCounts' => $statusCounts,
            'statuses' => TicketStatus::cases(),
            'priorities' => TicketPriority::cases(),
            'assignees' => $assignees,
        ]);
    }

    public function show(Ticket $ticket): View
    {
        $ticket->load([
            'customer:id,name,email',
            'assignee:id,name,email',
            'category:id,name',
            'sla:id,name,response_minutes,resolution_minutes',
            'messages' => fn ($query) => $query->with(['author:id,name,email', 'attachments'])->orderBy('created_at'),
        ]);

        return view('admin.tickets.show', [
            'ticket' => $ticket,
            'statuses' => TicketStatus::cases(),
            'priorities' => TicketPriority::cases(),
        ]);
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'min:2'],
            'status' => ['nullable', 'in:'.implode(',', TicketStatus::values())],
            'priority' => ['nullable', 'in:'.implode(',', TicketPriority::values())],
            'attachments.*' => ['nullable', 'file', 'max:3072'],
        ]);

        $message = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
            'body' => $data['message'],
            'is_internal' => $request->boolean('is_internal', false),
            'message_type' => 'message',
        ]);

        $this->storeAttachments($message, $request->file('attachments', []));

        $ticket->fill([
            'status' => $data['status'] ?? $ticket->status,
            'priority' => $data['priority'] ?? $ticket->priority,
            'last_staff_reply_at' => now(),
        ]);

        if (!$message->is_internal) {
            $ticket->last_customer_reply_at = $ticket->last_customer_reply_at ?? now();
        }

        $ticket->save();

        // Notify customer about reply
        if (!$message->is_internal && $ticket->customer) {
            $ticket->customer->notify(new \App\Notifications\TicketRepliedNotification($ticket, $message));
        }

        return redirect()->route('admin.support.tickets.show', $ticket)->with('success', 'Reply posted successfully.');
    }

    protected function statusCounts(): Collection
    {
        return Ticket::query()
            ->select('status', DB::raw('count(*) as aggregate'))
            ->groupBy('status')
            ->pluck('aggregate', 'status');
    }

    protected function storeAttachments(TicketMessage $message, array $files): void
    {
        foreach ($files as $file) {
            if (!$file) {
                continue;
            }

            $path = $file->store('tickets', 'public');

            TicketAttachment::create([
                'ticket_message_id' => $message->id,
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);
        }
    }
}

