<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketMessage;
use App\Models\TicketAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = Ticket::query()
            ->where('customer_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('customer.support.index', [
            'tickets' => $tickets,
        ]);
    }

    public function create()
    {
        $categories = TicketCategory::query()->active()->orderBy('name')->get();

        return view('customer.support.create', [
            'categories' => $categories,
            'priorities' => TicketPriority::cases(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:ticket_categories,id'],
            'priority' => ['nullable', 'in:'.implode(',', TicketPriority::values())],
            'message' => ['required', 'string', 'min:10'],
            'attachments.*' => ['nullable', 'file', 'max:3072'],
        ]);

        $user = $request->user();

        $priority = $data['priority'] ?? TicketPriority::MEDIUM->value;
        $categoryId = filled($data['category_id'] ?? null) ? (int) $data['category_id'] : null;
        $category = $categoryId ? TicketCategory::find($categoryId) : null;

        if (!$data['priority'] && $category?->default_priority) {
            $priority = $category->default_priority;
        }

        $ticket = Ticket::create([
            'subject' => $data['subject'],
            'description' => $data['message'],
            'status' => TicketStatus::OPEN,
            'priority' => $priority,
            'customer_id' => $user->id,
            'ticket_category_id' => $categoryId,
            'ticket_sla_id' => Config::get('support.default_sla_id'),
        ]);

        $message = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'body' => $data['message'],
            'is_internal' => false,
            'message_type' => 'message',
        ]);

        $this->storeAttachments($message, $request->file('attachments', []));

        $ticket->update([
            'last_customer_reply_at' => now(),
        ]);

        return redirect()->route('customer.support.tickets.show', $ticket)->with('success', 'Ticket created successfully.');
    }

    public function show(Request $request, Ticket $ticket)
    {
        abort_unless($ticket->customer_id === $request->user()->id, 404);

        $ticket->load(['messages' => function ($query) {
            $query->with(['author:id,name,email', 'attachments'])->orderBy('created_at');
        }, 'category']);

        return view('customer.support.show', [
            'ticket' => $ticket,
            'priorities' => TicketPriority::cases(),
        ]);
    }

    public function reply(Request $request, Ticket $ticket)
    {
        abort_unless($ticket->customer_id === $request->user()->id, 404);

        $data = $request->validate([
            'message' => ['required', 'string', 'min:5'],
            'attachments.*' => ['nullable', 'file', 'max:3072'],
        ]);

        $message = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
            'body' => $data['message'],
            'is_internal' => false,
            'message_type' => 'message',
        ]);

        $this->storeAttachments($message, $request->file('attachments', []));

        $ticket->update([
            'status' => TicketStatus::OPEN,
            'last_customer_reply_at' => now(),
        ]);

        // Notify assigned staff or all admins about customer reply
        if ($ticket->assigned_to) {
            $assignee = \App\Models\User::find($ticket->assigned_to);
            if ($assignee) {
                $assignee->notify(new \App\Notifications\TicketRepliedNotification($ticket, $message));
            }
        } else {
            // Notify all admins if no one is assigned
            $admins = \App\Models\User::where('is_admin', true)->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\TicketRepliedNotification($ticket, $message));
            }
        }

        return redirect()->route('customer.support.tickets.show', $ticket)->with('success', 'Reply sent successfully.');
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

