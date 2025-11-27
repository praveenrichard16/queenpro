<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_register_and_is_logged_in(): void
    {
        $response = $this->post(route('register.perform'), [
            'name' => 'New Customer',
            'email' => 'newcustomer@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'marketing_opt_in' => '0',
        ]);

        $response->assertRedirect(route('customer.dashboard'));

        $this->assertAuthenticated();

        $user = User::whereEmail('newcustomer@example.com')->first();

        $this->assertNotNull($user);
        $this->assertFalse($user->marketing_opt_in);
        $this->assertTrue(password_verify('secret123', $user->password));
    }
}

