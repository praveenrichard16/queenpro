<?php

namespace Tests\Feature\Admin;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketMessage;
use App\Models\TicketSla;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SupportTicketControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin@example.com',
        ]);
    }

    public function test_admin_can_view_ticket_index_with_filters(): void
    {
        $category = TicketCategory::create(['name' => 'General']);
        $ticket = Ticket::factory()->create([
            'ticket_category_id' => $category->id,
            'status' => TicketStatus::OPEN,
            'priority' => TicketPriority::HIGH,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.support.tickets.index', [
                'status' => TicketStatus::OPEN->value,
                'priority' => TicketPriority::HIGH->value,
                'search' => $ticket->ticket_number,
            ]));

        $response->assertOk();
        $response->assertSee($ticket->ticket_number);
        $response->assertSee('Support Tickets');
        $response->assertViewHas('tickets');
    }

    public function test_admin_can_view_ticket_show_page(): void
    {
        $ticket = Ticket::factory()->create([
            'subject' => 'Help with my order',
            'description' => 'Initial description',
        ]);

        TicketMessage::factory()->create([
            'ticket_id' => $ticket->id,
            'body' => 'First response',
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.support.tickets.show', $ticket));

        $response->assertOk();
        $response->assertSee('Help with my order');
        $response->assertSee('First response');
    }

    public function test_support_settings_can_be_updated(): void
    {
        TicketSla::create([
            'name' => 'Default SLA',
            'response_minutes' => 120,
            'resolution_minutes' => 1440,
            'is_default' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.support.settings.update'), [
                'support_email_enabled' => '1',
                'support_notify_addresses' => 'alerts@example.com',
                'support_email_customer_updates' => '1',
                'support_default_sla_id' => TicketSla::first()->id,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_ticket_creation_triggers_notifications(): void
    {
        Notification::fake();

        Config::set('support.email.enabled', true);
        Config::set('support.email.notify_addresses', 'alerts@example.com');
        Config::set('support.email.send_customer_updates', true);

        $customer = User::factory()->create(['email' => 'customer@example.com']);

        $ticket = Ticket::factory()->create([
            'customer_id' => $customer->id,
        ]);

        $this->assertNotNull($ticket->id);

        Notification::assertSentTimes(\App\Notifications\Tickets\TicketCreatedNotification::class, 1);
        Notification::assertSentOnDemand(\App\Notifications\Tickets\TicketCreatedNotification::class);
    }
}

