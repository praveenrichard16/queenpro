<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Process payment for an order
     *
     * @param Order $order
     * @param string $paymentMethod
     * @param array $paymentData
     * @return array ['success' => bool, 'transaction_id' => string|null, 'message' => string]
     */
    public function processPayment(Order $order, string $paymentMethod, array $paymentData = []): array
    {
        switch ($paymentMethod) {
            case 'cash_on_delivery':
                return $this->processCashOnDelivery($order);
            
            case 'razorpay':
            case 'credit_card':
            case 'online':
                return $this->processRazorpay($order, $paymentData);
            
            default:
                return [
                    'success' => false,
                    'transaction_id' => null,
                    'message' => 'Unsupported payment method.'
                ];
        }
    }

    /**
     * Process cash on delivery payment
     */
    protected function processCashOnDelivery(Order $order): array
    {
        // Cash on delivery doesn't require payment processing
        // Order status will remain 'pending' until payment is received
        return [
            'success' => true,
            'transaction_id' => 'COD-' . $order->order_number,
            'message' => 'Order placed successfully. Payment will be collected on delivery.'
        ];
    }

    /**
     * Process Razorpay payment
     */
    protected function processRazorpay(Order $order, array $paymentData): array
    {
        $razorpayConfig = config('payment.razorpay');
        
        if (!$razorpayConfig['enabled'] || !$razorpayConfig['key_id'] || !$razorpayConfig['key_secret']) {
            return [
                'success' => false,
                'transaction_id' => null,
                'message' => 'Razorpay payment gateway is not configured.'
            ];
        }

        $razorpayPaymentId = $paymentData['razorpay_payment_id'] ?? $paymentData['payment_id'] ?? null;
        $razorpayOrderId = $paymentData['razorpay_order_id'] ?? $paymentData['order_id'] ?? null;
        $razorpaySignature = $paymentData['razorpay_signature'] ?? $paymentData['signature'] ?? null;
        
        if (!$razorpayPaymentId || !$razorpayOrderId || !$razorpaySignature) {
            return [
                'success' => false,
                'transaction_id' => null,
                'message' => 'Razorpay payment details are required.'
            ];
        }

        try {
            // Verify signature
            $generatedSignature = hash_hmac('sha256', $razorpayOrderId . '|' . $razorpayPaymentId, $razorpayConfig['key_secret']);
            
            if ($generatedSignature !== $razorpaySignature) {
                Log::error('Razorpay signature verification failed', [
                    'order_id' => $order->id,
                    'razorpay_payment_id' => $razorpayPaymentId,
                ]);

                return [
                    'success' => false,
                    'transaction_id' => null,
                    'message' => 'Payment signature verification failed. Please try again.'
                ];
            }

            // Verify payment with Razorpay API
            $baseUrl = 'https://api.razorpay.com';
            $response = Http::withBasicAuth($razorpayConfig['key_id'], $razorpayConfig['key_secret'])
                ->get("{$baseUrl}/v1/payments/{$razorpayPaymentId}");

            if ($response->failed()) {
                Log::error('Razorpay payment verification failed', [
                    'order_id' => $order->id,
                    'razorpay_payment_id' => $razorpayPaymentId,
                    'response' => $response->body()
                ]);

                return [
                    'success' => false,
                    'transaction_id' => null,
                    'message' => 'Payment verification failed. Please try again.'
                ];
            }

            $payment = $response->json();

            if ($payment['status'] === 'captured' || $payment['status'] === 'authorized') {
                return [
                    'success' => true,
                    'transaction_id' => $razorpayPaymentId,
                    'message' => 'Payment processed successfully.'
                ];
            } else {
                return [
                    'success' => false,
                    'transaction_id' => $razorpayPaymentId,
                    'message' => 'Payment not completed. Status: ' . $payment['status']
                ];
            }
        } catch (\Exception $e) {
            Log::error('Razorpay payment processing error', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'transaction_id' => null,
                'message' => 'Payment processing error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Create Razorpay order
     */
    public function createRazorpayOrder(Order $order): array
    {
        $razorpayConfig = config('payment.razorpay');
        
        if (!$razorpayConfig['enabled'] || !$razorpayConfig['key_id'] || !$razorpayConfig['key_secret']) {
            return [
                'success' => false,
                'order_id' => null,
                'message' => 'Razorpay payment gateway is not configured.'
            ];
        }

        try {
            $baseUrl = 'https://api.razorpay.com';
            $amount = (int) ($order->total * 100); // Convert to paise
            
            $response = Http::withBasicAuth($razorpayConfig['key_id'], $razorpayConfig['key_secret'])
                ->post("{$baseUrl}/v1/orders", [
                    'amount' => $amount,
                    'currency' => strtoupper(config('app.currency', 'INR')),
                    'receipt' => $order->order_number,
                    'notes' => [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                    ],
                ]);

            if ($response->failed()) {
                Log::error('Razorpay order creation failed', [
                    'order_id' => $order->id,
                    'response' => $response->body()
                ]);

                return [
                    'success' => false,
                    'order_id' => null,
                    'message' => 'Failed to create Razorpay order.'
                ];
            }

            $razorpayOrder = $response->json();

            return [
                'success' => true,
                'order_id' => $razorpayOrder['id'],
                'key_id' => $razorpayConfig['key_id'],
                'amount' => $amount,
                'currency' => $razorpayOrder['currency'],
                'message' => 'Razorpay order created successfully.'
            ];
        } catch (\Exception $e) {
            Log::error('Razorpay order creation error', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'order_id' => null,
                'message' => 'Order creation error: ' . $e->getMessage()
            ];
        }
    }
}

