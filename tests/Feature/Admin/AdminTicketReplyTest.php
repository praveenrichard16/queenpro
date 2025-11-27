<?php

namespace Tests\Feature\Admin;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AdminTicketReplyTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;
    protected Ticket $ticket;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['is_admin' => true, 'is_staff' => true]);
        $this->customer = User::factory()->create();

        $this->ticket = Ticket::factory()->create([
            'customer_id' => $this->customer->id,
            'status' => TicketStatus::OPEN,
        ]);
    }

    public function test_admin_can_reply_to_ticket(): void
    {
        Notification::fake();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.support.tickets.reply', $this->ticket), [
                'message' => 'Thanks for reaching out. We are on it.',
                'status' => TicketStatus::IN_PROGRESS->value,
                'priority' => $this->ticket->priority->value,
            ]);

        $response->assertRedirect(route('admin.support.tickets.show', $this->ticket));
        $response->assertSessionHas('success');

        $this->ticket->refresh();

        $this->assertEquals(TicketStatus::IN_PROGRESS, $this->ticket->status);
        $this->assertNotNull($this->ticket->last_staff_reply_at);

        $message = TicketMessage::where('ticket_id', $this->ticket->id)->latest()->first();

        $this->assertNotNull($message);
        $this->assertEquals('Thanks for reaching out. We are on it.', $message->body);
        $this->assertFalse($message->is_internal);
    }
}

