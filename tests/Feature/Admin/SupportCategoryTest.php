<?php

namespace Tests\Feature\Admin;

use App\Enums\TicketPriority;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupportCategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function admin(): User
    {
        return User::factory()->create([
            'is_admin' => true,
            'is_staff' => true,
        ]);
    }

    public function test_admin_can_create_category(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->post(route('admin.support.categories.store'), [
            'name' => 'Billing',
            'description' => 'Payment related questions',
            'default_priority' => TicketPriority::HIGH->value,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.support.categories.index'));

        $this->assertDatabaseHas('ticket_categories', [
            'name' => 'Billing',
            'default_priority' => TicketPriority::HIGH->value,
            'is_active' => 1,
        ]);
    }

    public function test_admin_can_archive_category(): void
    {
        $admin = $this->admin();
        $category = TicketCategory::factory()->create(['is_active' => true]);

        $response = $this->actingAs($admin)
            ->delete(route('admin.support.categories.destroy', $category));

        $response->assertRedirect(route('admin.support.categories.index'));
        $this->assertDatabaseHas('ticket_categories', [
            'id' => $category->id,
            'is_active' => 0,
        ]);
    }

    public function test_customer_ticket_uses_category_default_priority(): void
    {
        $category = TicketCategory::factory()->create([
            'is_active' => true,
            'default_priority' => TicketPriority::URGENT->value,
        ]);

        $customer = User::factory()->create();

        $this->actingAs($customer)
            ->post(route('customer.support.tickets.store'), [
                'subject' => 'Urgent help needed',
                'category_id' => $category->id,
                'priority' => null,
                'message' => 'Please get back to me asap.',
            ])
            ->assertRedirect();

        $ticket = Ticket::first();

        $this->assertEquals(TicketPriority::URGENT->value, $ticket->priority);
        $this->assertEquals($category->id, $ticket->ticket_category_id);
    }
}

