<?php

namespace Tests\Feature\Customer;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerOrderVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_sees_only_their_orders(): void
    {
        $user = User::factory()->create(['email' => 'alice@example.com']);
        $other = User::factory()->create(['email' => 'bob@example.com']);

        $myOrder = Order::factory()->create(['customer_email' => 'alice@example.com', 'order_number' => 'ORD-ALICE']);
        $otherOrder = Order::factory()->create(['customer_email' => 'bob@example.com', 'order_number' => 'ORD-BOB']);

        $response = $this->actingAs($user)->get(route('customer.orders.index'));
        $response->assertSee('ORD-ALICE');
        $response->assertDontSee('ORD-BOB');

        $this->actingAs($user)->get(route('customer.orders.show', $myOrder))->assertOk();
        $this->actingAs($user)->get(route('customer.orders.show', $otherOrder))->assertNotFound();
    }
}

