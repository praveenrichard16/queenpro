<?php

namespace App\Services\Integration;

use App\Services\Integration\Exceptions\IntegrationException;
use Illuminate\Support\Facades\Mail;

class MailTestService
{
    /**
     * @param  array<string, mixed>  $config
     */
    public function sendTestEmail(array $config, string $recipient): void
    {
        if (!$recipient) {
            throw new IntegrationException('A recipient email address is required to send a test message.');
        }

        $mailerConfig = $this->prepareMailerConfig($config);

        $this->applyMailerConfig($mailerConfig);

        try {
            Mail::raw('This is a test email from the WowDash admin integrations panel.', function ($message) use ($recipient, $config): void {
                $message->to($recipient)
                    ->subject('Test Email - WowDash Integrations');

                if (!empty($config['from_email'])) {
                    $message->from($config['from_email'], $config['from_name'] ?? null);
                }
            });
        } catch (\Throwable $throwable) {
            throw IntegrationException::fromThrowable('Unable to send the test email.', $throwable);
        }
    }

    /**
     * @param  array<string, mixed>  $config
     * @return array<string, mixed>
     */
    protected function prepareMailerConfig(array $config): array
    {
        $encryption = $config['encryption'] ?? 'tls';
        if ($encryption === 'none') {
            $encryption = null;
        }

        $appUrl = config('app.url', 'https://example.com');
        $parsedHost = parse_url($appUrl, PHP_URL_HOST) ?: 'example.com';

        return [
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => $config['host'] ?? 'smtp.mailgun.org',
            'mail.mailers.smtp.port' => (int) ($config['port'] ?? 587),
            'mail.mailers.smtp.encryption' => $encryption,
            'mail.mailers.smtp.username' => $config['username'] ?? null,
            'mail.mailers.smtp.password' => $config['password'] ?? null,
            'mail.mailers.smtp.timeout' => null,
            'mail.mailers.smtp.auth_mode' => null,
            'mail.from.address' => $config['from_email'] ?: ('noreply@' . $parsedHost),
            'mail.from.name' => $config['from_name'] ?? config('app.name'),
        ];
    }

    /**
     * @param  array<string, mixed>  $config
     */
    protected function applyMailerConfig(array $config): void
    {
        foreach ($config as $key => $value) {
            config([$key => $value]);
        }
    }
}

