<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\Integration\Exceptions\IntegrationException;
use App\Services\Integration\FcmService;
use App\Services\Integration\MailTestService;
use App\Services\Integration\MetaWhatsAppService;
use App\Services\Integration\PaymentGatewayVerifier;
use App\Services\Integration\TwilioService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class IntegrationController extends Controller
{
    public function index(): View
    {
        $integrations = [
            'smtp' => $this->getJsonSetting('integration_email_smtp', [
                'enabled' => false,
                'host' => null,
                'port' => 587,
                'encryption' => 'tls',
                'username' => null,
                'password' => null,
                'from_name' => null,
                'from_email' => null,
            ]),
            'whatsapp_twilio' => $this->getJsonSetting('integration_whatsapp_twilio', [
                'enabled' => false,
                'account_sid' => null,
                'auth_token' => null,
                'from_number' => null,
                'sandbox_number' => null,
            ]),
            'whatsapp_meta' => $this->getJsonSetting('integration_whatsapp_meta', [
                'enabled' => false,
                'phone_number_id' => null,
                'whatsapp_business_account_id' => null,
                'business_account_id' => null,
                'app_id' => null,
                'app_secret' => null,
                'access_token' => null,
                'webhook_verify_token' => null,
                'api_version' => 'v19.0',
                'language' => 'en',
                'template_order_created' => null,
                'template_order_status_updated' => null,
            ]),
            'sms_twilio' => $this->getJsonSetting('integration_sms_twilio', [
                'enabled' => false,
                'account_sid' => null,
                'auth_token' => null,
                'from_number' => null,
                'messaging_service_sid' => null,
            ]),
            'push_fcm' => $this->getJsonSetting('integration_push_fcm', [
                'enabled' => false,
                'server_key' => null,
                'sender_id' => null,
                'project_id' => null,
            ]),
            'social_google' => $this->getJsonSetting('integration_social_google', [
                'enabled' => false,
                'client_id' => null,
                'client_secret' => null,
                'redirect_uri' => null,
            ]),
            'social_facebook' => $this->getJsonSetting('integration_social_facebook', [
                'enabled' => false,
                'app_id' => null,
                'app_secret' => null,
                'redirect_uri' => null,
            ]),
            'payments_razorpay' => $this->getJsonSetting('integration_payments_razorpay', [
                'enabled' => false,
                'mode' => 'sandbox',
                'key_id' => null,
                'key_secret' => null,
                'webhook_secret' => null,
            ]),
        ];

        // Get WhatsApp status information - refresh settings to get latest
        \Illuminate\Support\Facades\Cache::forget('setting_integration_whatsapp_meta');
        $whatsappMeta = $this->getJsonSetting('integration_whatsapp_meta', [
            'enabled' => false,
            'phone_number_id' => null,
            'whatsapp_business_account_id' => null,
            'business_account_id' => null,
            'app_id' => null,
            'app_secret' => null,
            'access_token' => null,
            'webhook_verify_token' => null,
            'api_version' => 'v19.0',
            'language' => 'en',
            'template_order_created' => null,
            'template_order_status_updated' => null,
        ]);
        $whatsappStatus = $this->getWhatsAppStatus($whatsappMeta);

        return view('admin.integrations.index', [
            'integrations' => $integrations,
            'whatsappStatus' => $whatsappStatus,
        ]);
    }

    public function updateSmtp(Request $request): RedirectResponse
    {
        $payload = $this->validatedSmtp($request);

        $this->saveJsonSetting('integration_email_smtp', $payload);
        $this->syncMailConfig($payload);

        return $this->redirectSuccess('email', 'SMTP settings updated successfully.');
    }

    public function testSmtp(Request $request, MailTestService $mailTestService): RedirectResponse
    {
        $payload = $this->validatedSmtp($request);

        $this->saveJsonSetting('integration_email_smtp', $payload);
        $this->syncMailConfig($payload);

        $recipient = $request->input('test_email') ?: (Auth::user()?->email ?? $payload['from_email']);

        try {
            $mailTestService->sendTestEmail($payload, $recipient);
        } catch (IntegrationException $exception) {
            return $this->redirectError('email', $exception->getMessage());
        }

        return $this->redirectSuccess('email', "Test email dispatched to {$recipient}.");
    }

    public function updateTwilioWhatsApp(Request $request): RedirectResponse
    {
        $payload = $this->validatedTwilioWhatsApp($request);

        $this->saveJsonSetting('integration_whatsapp_twilio', $payload);

        return $this->redirectSuccess('whatsapp', 'Twilio WhatsApp settings saved.');
    }

    public function testTwilioWhatsApp(Request $request, TwilioService $twilio): RedirectResponse
    {
        $payload = $this->validatedTwilioWhatsApp($request);

        $this->saveJsonSetting('integration_whatsapp_twilio', $payload);

        try {
            $twilio->verifyCredentials($payload);
        } catch (IntegrationException $exception) {
            return $this->redirectError('whatsapp', $exception->getMessage());
        }

        return $this->redirectSuccess('whatsapp', 'Twilio WhatsApp credentials verified successfully.');
    }

    public function updateMetaWhatsApp(Request $request): RedirectResponse
    {
        $payload = $this->validatedMetaWhatsApp($request);

        $this->saveJsonSetting('integration_whatsapp_meta', $payload);
        
        // Clear config cache to ensure fresh data is loaded
        \Illuminate\Support\Facades\Cache::forget('setting_integration_whatsapp_meta');

        return $this->redirectSuccess('whatsapp', 'Meta WhatsApp Cloud API settings saved.');
    }

    public function testMetaWhatsApp(Request $request, MetaWhatsAppService $meta): RedirectResponse
    {
        $payload = $this->validatedMetaWhatsApp($request);
        $testPhoneNumber = $request->input('test_phone_number');

        if (empty($testPhoneNumber)) {
            return $this->redirectError('whatsapp', 'Please enter a phone number to send test message.');
        }

        $this->saveJsonSetting('integration_whatsapp_meta', $payload);

        try {
            // Verify credentials first
            $meta->verifyCredentials($payload);
            
            // Send test message
            $meta->sendTestMessage(
                $payload,
                $testPhoneNumber,
                'Hello! This is a test message from your WhatsApp Business integration. Your WhatsApp setup is working correctly! ✅'
            );
        } catch (IntegrationException $exception) {
            return $this->redirectError('whatsapp', $exception->getMessage());
        }

        return $this->redirectSuccess('whatsapp', 'Test message sent successfully to ' . $testPhoneNumber . '. Please check your WhatsApp.');
    }

    public function updateTwilioSms(Request $request): RedirectResponse
    {
        $payload = $this->validatedTwilioSms($request);

        $this->saveJsonSetting('integration_sms_twilio', $payload);

        return $this->redirectSuccess('sms', 'Twilio SMS settings saved.');
    }

    public function testTwilioSms(Request $request, TwilioService $twilio): RedirectResponse
    {
        $payload = $this->validatedTwilioSms($request);

        $this->saveJsonSetting('integration_sms_twilio', $payload);

        try {
            $twilio->verifyCredentials($payload);
        } catch (IntegrationException $exception) {
            return $this->redirectError('sms', $exception->getMessage());
        }

        return $this->redirectSuccess('sms', 'Twilio SMS credentials verified successfully.');
    }

    public function updatePushFcm(Request $request): RedirectResponse
    {
        $payload = $this->validatedPushFcm($request);

        $this->saveJsonSetting('integration_push_fcm', $payload);

        return $this->redirectSuccess('push', 'Firebase Cloud Messaging settings saved.');
    }

    public function testPushFcm(Request $request, FcmService $fcm): RedirectResponse
    {
        $payload = $this->validatedPushFcm($request);

        $this->saveJsonSetting('integration_push_fcm', $payload);

        try {
            $fcm->sendTestNotification($payload, 'WowDash Admin', 'This is a test push notification.');
        } catch (IntegrationException $exception) {
            return $this->redirectError('push', $exception->getMessage());
        }

        return $this->redirectSuccess('push', 'Firebase server key verified successfully.');
    }

    public function updateSocialGoogle(Request $request): RedirectResponse
    {
        $payload = $this->validatedSocialGoogle($request);

        $this->saveJsonSetting('integration_social_google', $payload);
        $this->syncSocialConfig('google', $payload);

        return $this->redirectSuccess('social', 'Google Login settings saved.');
    }

    public function testSocialGoogle(Request $request): RedirectResponse
    {
        $payload = $this->validatedSocialGoogle($request);

        $this->saveJsonSetting('integration_social_google', $payload);
        $this->syncSocialConfig('google', $payload);

        if (empty($payload['client_id']) || !str_ends_with($payload['client_id'], '.apps.googleusercontent.com')) {
            return $this->redirectError('social', 'Google client ID should end with .apps.googleusercontent.com');
        }

        return $this->redirectSuccess('social', 'Google OAuth credentials stored. Complete the OAuth flow to finalise verification.');
    }

    public function updateSocialFacebook(Request $request): RedirectResponse
    {
        $payload = $this->validatedSocialFacebook($request);

        $this->saveJsonSetting('integration_social_facebook', $payload);
        $this->syncSocialConfig('facebook', $payload);

        return $this->redirectSuccess('social', 'Facebook Login settings saved.');
    }

    public function testSocialFacebook(Request $request): RedirectResponse
    {
        $payload = $this->validatedSocialFacebook($request);

        $this->saveJsonSetting('integration_social_facebook', $payload);
        $this->syncSocialConfig('facebook', $payload);

        try {
            $response = \Illuminate\Support\Facades\Http::asForm()->post('https://graph.facebook.com/oauth/access_token', [
                'client_id' => $payload['app_id'],
                'client_secret' => $payload['app_secret'],
                'grant_type' => 'client_credentials',
            ]);

            if ($response->failed()) {
                return $this->redirectError('social', 'Facebook returned an error: ' . $response->body());
            }
        } catch (\Throwable $exception) {
            return $this->redirectError('social', 'Unable to reach Facebook OAuth: ' . $exception->getMessage());
        }

        return $this->redirectSuccess('social', 'Facebook OAuth credentials verified successfully.');
    }

    public function updatePaymentsRazorpay(Request $request): RedirectResponse
    {
        $payload = $this->validatedPaymentsRazorpay($request);

        $this->saveJsonSetting('integration_payments_razorpay', $payload);
        $this->syncPaymentConfig('razorpay', $payload);

        return $this->redirectSuccess('payments', 'Razorpay settings saved.');
    }

    public function testPaymentsRazorpay(Request $request, PaymentGatewayVerifier $verifier): RedirectResponse
    {
        $payload = $this->validatedPaymentsRazorpay($request);

        $this->saveJsonSetting('integration_payments_razorpay', $payload);
        $this->syncPaymentConfig('razorpay', $payload);

        try {
            $verifier->verifyRazorpay($payload);
        } catch (IntegrationException $exception) {
            return $this->redirectError('payments', $exception->getMessage());
        }

        return $this->redirectSuccess('payments', 'Razorpay credentials verified successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validatedSmtp(Request $request): array
    {
        $data = $request->validate([
            'enabled' => ['nullable'],
            'host' => ['nullable', 'string', 'max:255'],
            'port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'encryption' => ['nullable', Rule::in(['tls', 'ssl', 'none'])],
            'username' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'max:255'],
            'from_name' => ['nullable', 'string', 'max:255'],
            'from_email' => ['nullable', 'email', 'max:255'],
            'active_tab' => ['nullable', 'string'],
            'test_email' => ['nullable', 'email', 'max:255'],
        ]);

        return [
            'enabled' => $request->boolean('enabled'),
            'host' => $data['host'] ?? null,
            'port' => $data['port'] ?? 587,
            'encryption' => $data['encryption'] ?? 'tls',
            'username' => $data['username'] ?? null,
            'password' => $data['password'] ?? null,
            'from_name' => $data['from_name'] ?? null,
            'from_email' => $data['from_email'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function validatedTwilioWhatsApp(Request $request): array
    {
        $data = $request->validate([
            'enabled' => ['nullable'],
            'account_sid' => ['nullable', 'string', 'max:64'],
            'auth_token' => ['nullable', 'string', 'max:128'],
            'from_number' => ['nullable', 'string', 'max:32'],
            'sandbox_number' => ['nullable', 'string', 'max:32'],
            'active_tab' => ['nullable', 'string'],
        ]);

        return [
            'enabled' => $request->boolean('enabled'),
            'account_sid' => $data['account_sid'] ?? null,
            'auth_token' => $data['auth_token'] ?? null,
            'from_number' => $data['from_number'] ?? null,
            'sandbox_number' => $data['sandbox_number'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function validatedMetaWhatsApp(Request $request): array
    {
        $data = $request->validate([
            'enabled' => ['nullable'],
            'phone_number_id' => ['nullable', 'string', 'max:64'],
            'whatsapp_business_account_id' => ['nullable', 'string', 'max:64'],
            'business_account_id' => ['nullable', 'string', 'max:64'],
            'app_id' => ['nullable', 'string', 'max:64'],
            'app_secret' => ['nullable', 'string', 'max:128'],
            'access_token' => ['nullable', 'string', 'max:512'],
            'webhook_verify_token' => ['nullable', 'string', 'max:128'],
            'api_version' => ['nullable', 'string', 'max:32'],
            'language' => ['nullable', 'string', 'max:8'],
            'template_order_created' => ['nullable', 'string', 'max:255'],
            'template_order_status_updated' => ['nullable', 'string', 'max:255'],
            'active_tab' => ['nullable', 'string'],
        ]);

        return [
            'enabled' => $request->boolean('enabled'),
            'phone_number_id' => $data['phone_number_id'] ?? null,
            'whatsapp_business_account_id' => $data['whatsapp_business_account_id'] ?? null,
            'business_account_id' => $data['business_account_id'] ?? null,
            'app_id' => $data['app_id'] ?? null,
            'app_secret' => $data['app_secret'] ?? null,
            'access_token' => $data['access_token'] ?? null,
            'webhook_verify_token' => $data['webhook_verify_token'] ?? null,
            'api_version' => $data['api_version'] ?? 'v19.0',
            'language' => $data['language'] ?? 'en',
            'template_order_created' => $data['template_order_created'] ?? null,
            'template_order_status_updated' => $data['template_order_status_updated'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function validatedTwilioSms(Request $request): array
    {
        $data = $request->validate([
            'enabled' => ['nullable'],
            'account_sid' => ['nullable', 'string', 'max:64'],
            'auth_token' => ['nullable', 'string', 'max:128'],
            'from_number' => ['nullable', 'string', 'max:32'],
            'messaging_service_sid' => ['nullable', 'string', 'max:64'],
            'active_tab' => ['nullable', 'string'],
        ]);

        return [
            'enabled' => $request->boolean('enabled'),
            'account_sid' => $data['account_sid'] ?? null,
            'auth_token' => $data['auth_token'] ?? null,
            'from_number' => $data['from_number'] ?? null,
            'messaging_service_sid' => $data['messaging_service_sid'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function validatedPushFcm(Request $request): array
    {
        $data = $request->validate([
            'enabled' => ['nullable'],
            'server_key' => ['nullable', 'string'],
            'sender_id' => ['nullable', 'string', 'max:128'],
            'project_id' => ['nullable', 'string', 'max:128'],
            'active_tab' => ['nullable', 'string'],
        ]);

        return [
            'enabled' => $request->boolean('enabled'),
            'server_key' => $data['server_key'] ?? null,
            'sender_id' => $data['sender_id'] ?? null,
            'project_id' => $data['project_id'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function validatedSocialGoogle(Request $request): array
    {
        $data = $request->validate([
            'enabled' => ['nullable'],
            'client_id' => ['nullable', 'string', 'max:255'],
            'client_secret' => ['nullable', 'string', 'max:255'],
            'redirect_uri' => ['nullable', 'url', 'max:255'],
            'active_tab' => ['nullable', 'string'],
        ]);

        return [
            'enabled' => $request->boolean('enabled'),
            'client_id' => $data['client_id'] ?? null,
            'client_secret' => $data['client_secret'] ?? null,
            'redirect_uri' => $data['redirect_uri'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function validatedSocialFacebook(Request $request): array
    {
        $data = $request->validate([
            'enabled' => ['nullable'],
            'app_id' => ['nullable', 'string', 'max:255'],
            'app_secret' => ['nullable', 'string', 'max:255'],
            'redirect_uri' => ['nullable', 'url', 'max:255'],
            'active_tab' => ['nullable', 'string'],
        ]);

        return [
            'enabled' => $request->boolean('enabled'),
            'app_id' => $data['app_id'] ?? null,
            'app_secret' => $data['app_secret'] ?? null,
            'redirect_uri' => $data['redirect_uri'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function validatedPaymentsRazorpay(Request $request): array
    {
        $data = $request->validate([
            'enabled' => ['nullable'],
            'mode' => ['nullable', Rule::in(['sandbox', 'live'])],
            'key_id' => ['nullable', 'string', 'max:255'],
            'key_secret' => ['nullable', 'string', 'max:255'],
            'webhook_secret' => ['nullable', 'string', 'max:255'],
            'active_tab' => ['nullable', 'string'],
        ]);

        return [
            'enabled' => $request->boolean('enabled'),
            'mode' => $data['mode'] ?? 'sandbox',
            'key_id' => $data['key_id'] ?? null,
            'key_secret' => $data['key_secret'] ?? null,
            'webhook_secret' => $data['webhook_secret'] ?? null,
        ];
    }

    protected function saveJsonSetting(string $key, array $payload): void
    {
        Setting::setValue($key, json_encode($payload), 'json');
    }

    protected function redirectSuccess(string $tab, string $message): RedirectResponse
    {
        session()->flash('active_tab', $tab);

        return back()->with('success', $message);
    }

    protected function redirectError(string $tab, string $message): RedirectResponse
    {
        session()->flash('active_tab', $tab);

        return back()->withInput()->with('error', $message);
    }

    protected function syncMailConfig(array $payload): void
    {
        config([
            'mail.mailers.smtp.host' => $payload['host'] ?: config('mail.mailers.smtp.host'),
            'mail.mailers.smtp.port' => $payload['port'] ?? config('mail.mailers.smtp.port'),
            'mail.mailers.smtp.encryption' => $payload['encryption'] === 'none' ? null : $payload['encryption'],
            'mail.mailers.smtp.username' => $payload['username'],
            'mail.mailers.smtp.password' => $payload['password'],
            'mail.from.address' => $payload['from_email'] ?: config('mail.from.address'),
            'mail.from.name' => $payload['from_name'] ?: config('mail.from.name'),
        ]);
    }

    protected function syncSocialConfig(string $provider, array $payload): void
    {
        $key = "services.{$provider}";

        $config = config($key, []);
        $config['client_id'] = $payload['client_id'] ?? $payload['app_id'] ?? null;
        $config['client_secret'] = $payload['client_secret'] ?? $payload['app_secret'] ?? null;
        $config['redirect'] = $payload['redirect_uri'] ?? null;

        config([$key => $config]);
    }

    protected function syncPaymentConfig(string $gateway, array $payload): void
    {
        $key = "payment.{$gateway}";
        $config = config($key, []);

        $map = match ($gateway) {
            'razorpay' => [
                'enabled' => $payload['enabled'],
                'mode' => $payload['mode'],
                'key_id' => $payload['key_id'],
                'key_secret' => $payload['key_secret'],
                'webhook_secret' => $payload['webhook_secret'],
            ],
            default => [],
        };

        config([$key => array_merge($config, $map)]);
    }

    protected function getJsonSetting(string $key, array $defaults = []): array
    {
        $value = Setting::getValue($key);

        if (!is_array($value)) {
            return $defaults;
        }

        return array_merge($defaults, $value);
    }

    protected function defaultDomain(): string
    {
        $request = request();

        if ($request) {
            return $request->getHost();
        }

        $url = config('app.url', 'http://localhost');

        return parse_url($url, PHP_URL_HOST) ?: 'localhost';
    }

    /**
     * Get WhatsApp connection and feature status
     */
    protected function getWhatsAppStatus(array $whatsappConfig): array
    {
        $status = [
            'connection' => [
                'status' => 'disconnected',
                'message' => 'Not configured',
                'icon' => 'solar:close-circle-bold',
                'color' => 'danger',
            ],
            'catalog' => [
                'status' => 'not_configured',
                'message' => 'Not configured',
                'count' => 0,
                'icon' => 'solar:close-circle-bold',
                'color' => 'secondary',
            ],
            'templates' => [
                'status' => 'not_configured',
                'message' => 'Not configured',
                'count' => 0,
                'icon' => 'solar:close-circle-bold',
                'color' => 'secondary',
            ],
            'order_notifications' => [
                'status' => 'not_configured',
                'message' => 'Not configured',
                'icon' => 'solar:close-circle-bold',
                'color' => 'secondary',
            ],
            'messages' => [
                'status' => 'not_configured',
                'message' => 'Not configured',
                'icon' => 'solar:close-circle-bold',
                'color' => 'secondary',
            ],
        ];

        // Check if WhatsApp is enabled and has basic config
        if (empty($whatsappConfig['enabled']) || empty($whatsappConfig['access_token']) || empty($whatsappConfig['phone_number_id'])) {
            return $status;
        }

        // Check API Connection - Use direct config values, not cached
        $phoneNumberId = $whatsappConfig['phone_number_id'] ?? null;
        $accessToken = $whatsappConfig['access_token'] ?? null;
        $apiVersion = $whatsappConfig['api_version'] ?? 'v19.0';

        if ($phoneNumberId && $accessToken) {
            try {
                $response = \Illuminate\Support\Facades\Http::withToken($accessToken)
                    ->timeout(10)
                    ->get("https://graph.facebook.com/{$apiVersion}/{$phoneNumberId}", [
                        'fields' => 'id,display_phone_number,verified_name',
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $displayNumber = $data['display_phone_number'] ?? 'N/A';
                    $status['connection'] = [
                        'status' => 'connected',
                        'message' => 'Connected (' . $displayNumber . ')',
                        'icon' => 'solar:check-circle-bold',
                        'color' => 'success',
                    ];
                    $status['messages'] = [
                        'status' => 'ready',
                        'message' => 'Ready to send',
                        'icon' => 'solar:check-circle-bold',
                        'color' => 'success',
                    ];
                } else {
                    $errorBody = $response->json();
                    $errorMessage = $errorBody['error']['message'] ?? 'Connection failed';
                    $errorCode = $errorBody['error']['code'] ?? null;
                    
                    $status['connection'] = [
                        'status' => 'error',
                        'message' => 'Failed: ' . $errorMessage . ($errorCode ? ' (Code: ' . $errorCode . ')' : ''),
                        'icon' => 'solar:danger-triangle-bold',
                        'color' => 'danger',
                    ];
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                $status['connection'] = [
                    'status' => 'error',
                    'message' => 'Network error: Unable to reach Meta API',
                    'icon' => 'solar:danger-triangle-bold',
                    'color' => 'danger',
                ];
            } catch (\Exception $e) {
                $status['connection'] = [
                    'status' => 'error',
                    'message' => 'Error: ' . $e->getMessage(),
                    'icon' => 'solar:danger-triangle-bold',
                    'color' => 'danger',
                ];
            }
        }

        // Check Catalog Status
        try {
            $syncedProducts = \App\Models\Product::where('is_synced_to_whatsapp', true)->count();
            $totalProducts = \App\Models\Product::count();
            
            if ($syncedProducts > 0) {
                $status['catalog'] = [
                    'status' => 'active',
                    'message' => "{$syncedProducts} of {$totalProducts} products synced",
                    'count' => $syncedProducts,
                    'icon' => 'solar:check-circle-bold',
                    'color' => 'success',
                ];
            } else {
                $status['catalog'] = [
                    'status' => 'empty',
                    'message' => 'No products synced',
                    'count' => 0,
                    'icon' => 'solar:info-circle-bold',
                    'color' => 'info',
                ];
            }
        } catch (\Exception $e) {
            // Ignore errors for catalog check
        }

        // Check Templates Status
        try {
            $templates = \App\Models\MarketingTemplate::where('type', 'whatsapp')->get();
            $approvedTemplates = $templates->where('whatsapp_template_status', 'APPROVED')->count();
            
            if ($templates->count() > 0) {
                $status['templates'] = [
                    'status' => $approvedTemplates > 0 ? 'active' : 'pending',
                    'message' => "{$templates->count()} templates ({$approvedTemplates} approved)",
                    'count' => $templates->count(),
                    'icon' => $approvedTemplates > 0 ? 'solar:check-circle-bold' : 'solar:clock-circle-bold',
                    'color' => $approvedTemplates > 0 ? 'success' : 'warning',
                ];
            } else {
                $status['templates'] = [
                    'status' => 'empty',
                    'message' => 'No templates synced',
                    'count' => 0,
                    'icon' => 'solar:info-circle-bold',
                    'color' => 'info',
                ];
            }
        } catch (\Exception $e) {
            // Ignore errors for templates check
        }

        // Check Order Notifications Status
        $orderCreatedTemplate = $whatsappConfig['template_order_created'] ?? null;
        $orderStatusTemplate = $whatsappConfig['template_order_status_updated'] ?? null;
        
        if ($orderCreatedTemplate && $orderStatusTemplate) {
            $status['order_notifications'] = [
                'status' => 'configured',
                'message' => 'Both templates configured',
                'icon' => 'solar:check-circle-bold',
                'color' => 'success',
            ];
        } elseif ($orderCreatedTemplate || $orderStatusTemplate) {
            $status['order_notifications'] = [
                'status' => 'partial',
                'message' => 'Partially configured',
                'icon' => 'solar:warning-circle-bold',
                'color' => 'warning',
            ];
        } else {
            $status['order_notifications'] = [
                'status' => 'not_configured',
                'message' => 'Templates not configured',
                'icon' => 'solar:info-circle-bold',
                'color' => 'info',
            ];
        }

        return $status;
    }
}

