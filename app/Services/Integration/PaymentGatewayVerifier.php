<?php

namespace App\Services\Integration;

use App\Services\Integration\Exceptions\IntegrationException;
use Illuminate\Support\Facades\Http;

class PaymentGatewayVerifier
{
    /**
     * @param  array<string, mixed>  $credentials
     */
    public function verifyTabby(array $credentials): void
    {
        $secretKey = $credentials['secret_key'] ?? null;
        $region = $credentials['region'] ?? 'saudi-arabia';

        if (!$secretKey) {
            throw new IntegrationException('Tabby secret key is required.');
        }

        $baseUrl = $region === 'united-arab-emirates'
            ? 'https://api.tabby.ai'
            : 'https://api.tabby.ai';

        try {
            $response = Http::withToken($secretKey)
                ->get("{$baseUrl}/api/v2/merchant/profile");

            if ($response->failed()) {
                throw new IntegrationException('Tabby API rejected the credentials: ' . $response->body());
            }
        } catch (\Throwable $throwable) {
            throw IntegrationException::fromThrowable('Unable to reach Tabby API.', $throwable);
        }
    }

    /**
     * @param  array<string, mixed>  $credentials
     */
    public function verifyPayPal(array $credentials): void
    {
        $clientId = $credentials['client_id'] ?? null;
        $clientSecret = $credentials['client_secret'] ?? null;
        $mode = $credentials['mode'] ?? 'sandbox';

        if (!$clientId || !$clientSecret) {
            throw new IntegrationException('PayPal client ID and secret are required.');
        }

        $baseUrl = $mode === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        try {
            $response = Http::asForm()
                ->withBasicAuth($clientId, $clientSecret)
                ->post("{$baseUrl}/v1/oauth2/token", [
                    'grant_type' => 'client_credentials',
                ]);

            if ($response->failed()) {
                throw new IntegrationException('PayPal credentials are invalid: ' . $response->body());
            }
        } catch (\Throwable $throwable) {
            throw IntegrationException::fromThrowable('Unable to reach PayPal API.', $throwable);
        }
    }

    /**
     * @param  array<string, mixed>  $credentials
     */
    public function verifyStripe(array $credentials): void
    {
        $secretKey = $credentials['secret_key'] ?? null;

        if (!$secretKey) {
            throw new IntegrationException('Stripe secret key is required.');
        }

        try {
            $response = Http::withBasicAuth($secretKey, '')
                ->get('https://api.stripe.com/v1/account');

            if ($response->failed()) {
                throw new IntegrationException('Stripe credentials are invalid: ' . $response->body());
            }
        } catch (\Throwable $throwable) {
            throw IntegrationException::fromThrowable('Unable to reach Stripe API.', $throwable);
        }
    }

    /**
     * @param  array<string, mixed>  $credentials
     */
    public function verifyApplePay(array $credentials): void
    {
        $certificate = $credentials['merchant_certificate'] ?? '';
        $privateKey = $credentials['merchant_key'] ?? '';
        $merchantId = $credentials['merchant_id'] ?? null;

        if (!$merchantId) {
            throw new IntegrationException('Apple Pay Merchant ID is required.');
        }

        if (!$certificate || !$privateKey) {
            throw new IntegrationException('Apple Pay requires both merchant certificate and private key.');
        }

        $certResource = @openssl_x509_read($certificate);
        $keyResource = @openssl_pkey_get_private($privateKey);

        if (!$certResource) {
            throw new IntegrationException('Apple Pay merchant certificate is not valid PEM content.');
        }

        if (!$keyResource) {
            throw new IntegrationException('Apple Pay merchant private key is not valid PEM content.');
        }

        openssl_x509_free($certResource);
        openssl_pkey_free($keyResource);
    }

    /**
     * @param  array<string, mixed>  $credentials
     */
    public function verifyRazorpay(array $credentials): void
    {
        $keyId = $credentials['key_id'] ?? null;
        $keySecret = $credentials['key_secret'] ?? null;
        $mode = $credentials['mode'] ?? 'sandbox';

        if (!$keyId || !$keySecret) {
            throw new IntegrationException('Razorpay Key ID and Key Secret are required.');
        }

        try {
            $baseUrl = $mode === 'live'
                ? 'https://api.razorpay.com'
                : 'https://api.razorpay.com';

            $response = Http::withBasicAuth($keyId, $keySecret)
                ->get("{$baseUrl}/v1/payments");

            if ($response->failed()) {
                throw new IntegrationException('Razorpay credentials are invalid: ' . $response->body());
            }
        } catch (\Throwable $throwable) {
            throw IntegrationException::fromThrowable('Unable to reach Razorpay API.', $throwable);
        }
    }
}

