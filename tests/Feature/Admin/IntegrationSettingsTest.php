<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IntegrationSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_save_smtp_settings(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)
            ->post(route('admin.integrations.smtp.update'), [
                'enabled' => '1',
                'host' => 'smtp.example.com',
                'port' => 2525,
                'encryption' => 'tls',
                'username' => 'mailer',
                'password' => 'secret',
                'from_name' => 'WowDash QA',
                'from_email' => 'qa@example.com',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $stored = Setting::getValue('integration_email_smtp');

        $this->assertIsArray($stored);
        $this->assertSame('smtp.example.com', $stored['host']);
        $this->assertSame('qa@example.com', config('mail.from.address'));
        $this->assertSame('smtp.example.com', config('mail.mailers.smtp.host'));
    }

    public function test_tabby_credentials_verification_uses_http_client(): void
    {
        $admin = $this->adminUser();

        Http::fake([
            'https://api.tabby.ai/api/v2/merchant/profile' => Http::response([
                'merchant_code' => 'demo',
            ], 200),
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.integrations.payments.tabby.test'), [
                'enabled' => '1',
                'region' => 'saudi-arabia',
                'public_key' => 'pk_test_demo',
                'secret_key' => 'sk_test_demo',
                'merchant_code' => 'merchant-123',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertTrue(Http::isFaking());
    }

    protected function adminUser(): User
    {
        return User::factory()->create([
            'is_admin' => true,
            'email' => 'admin@example.com',
        ]);
    }
}

