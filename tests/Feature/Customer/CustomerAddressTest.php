<?php

namespace Tests\Feature\Customer;

use App\Models\CustomerAddress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerAddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_manage_addresses(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('customer.addresses.store'), [
                'label' => 'Home',
                'type' => 'shipping',
                'contact_name' => 'John Doe',
                'contact_phone' => '+123456789',
                'street' => '123 Main Street',
                'city' => 'Riyadh',
                'state' => 'RI',
                'postal_code' => '12345',
                'country' => 'Saudi Arabia',
                'is_default' => '1',
            ])
            ->assertRedirect();

        $address = CustomerAddress::first();
        $this->assertNotNull($address);
        $this->assertTrue($address->is_default);

        $this->actingAs($user)
            ->put(route('customer.addresses.update', $address), [
                'label' => 'Office',
                'type' => 'billing',
                'contact_name' => 'Jane Doe',
                'contact_phone' => '+987654321',
                'street' => '456 Business St',
                'city' => 'Jeddah',
                'state' => 'JE',
                'postal_code' => '54321',
                'country' => 'Saudi Arabia',
            ])
            ->assertRedirect();

        $address->refresh();
        $this->assertEquals('Office', $address->label);
        $this->assertEquals('billing', $address->type);

        $this->actingAs($user)
            ->delete(route('customer.addresses.destroy', $address))
            ->assertRedirect();

        $this->assertDatabaseCount('customer_addresses', 0);
    }
}

