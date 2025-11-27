<?php

namespace Tests\Feature\Customer;

use App\Enums\TicketPriority;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SupportTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_create_ticket_with_attachment(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $category = TicketCategory::factory()->create();

        $response = $this->actingAs($user)->post(route('customer.support.tickets.store'), [
            'subject' => 'Need help with my order',
            'category_id' => $category->id,
            'priority' => TicketPriority::HIGH->value,
            'message' => 'Order arrived damaged, please advise.',
            'attachments' => [UploadedFile::fake()->image('damage.jpg')],
        ]);

        $response->assertRedirect();
        $ticket = Ticket::first();
        $this->assertNotNull($ticket);
        $this->assertEquals($user->id, $ticket->customer_id);
        $this->assertEquals('Need help with my order', $ticket->subject);
        $this->assertEquals(TicketPriority::HIGH, $ticket->priority);

        $this->assertCount(1, $ticket->messages);
        $message = $ticket->messages->first();
        $this->assertEquals('Order arrived damaged, please advise.', $message->body);

        $this->assertCount(1, $message->attachments);
        Storage::disk('public')->assertExists($message->attachments->first()->path);
    }

    public function test_customer_can_reply_to_ticket(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create(['customer_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('customer.support.tickets.reply', $ticket), [
            'message' => 'Any update on this order?',
        ]);

        $response->assertRedirect();
        $ticket->refresh();
        $this->assertEquals($ticket->messages()->count(), 1);
        $this->assertEquals('Any update on this order?', $ticket->messages()->first()->body);
        $this->assertNotNull($ticket->last_customer_reply_at);
    }
}

