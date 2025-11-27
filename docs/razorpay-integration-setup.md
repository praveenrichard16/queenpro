# Razorpay Payment Gateway Integration Setup Guide

## Overview
This guide will help you integrate Razorpay payment gateway into your e-commerce store. Razorpay supports multiple payment methods including credit/debit cards, UPI, netbanking, wallets, and more.

## Prerequisites
- Razorpay account (sign up at https://razorpay.com)
- Business verification completed in Razorpay
- API keys (Key ID and Key Secret)

## Step 1: Create Razorpay Account

1. Go to [Razorpay Dashboard](https://dashboard.razorpay.com/)
2. Sign up for a new account or log in
3. Complete business verification (required for live mode)

## Step 2: Get API Credentials

### Test Mode (Sandbox)
1. Log in to Razorpay Dashboard
2. Go to **Settings → API Keys**
3. Click **Generate Test Key**
4. Copy your **Key ID** and **Key Secret**

### Live Mode
1. Complete business verification
2. Go to **Settings → API Keys**
3. Click **Generate Live Key**
4. Copy your **Key ID** and **Key Secret**
5. **Important**: Keep these credentials secure and never share them

## Step 3: Configure in Admin Panel

1. Navigate to **Admin Panel → Integrations → Payments**
2. Enable **Razorpay**
3. Select **Mode**: 
   - **Sandbox** - For testing (uses test API keys)
   - **Live** - For production (uses live API keys)
4. Enter your credentials:
   - **Key ID**: Your Razorpay Key ID (starts with `rzp_test_` for sandbox or `rzp_live_` for live)
   - **Key Secret**: Your Razorpay Key Secret
   - **Webhook Secret**: (Optional) For webhook verification
5. Click **Save Razorpay Settings**
6. Click **Verify Credentials** to test the connection

## Step 4: Set Up Webhooks (Recommended)

Webhooks allow Razorpay to notify your system about payment status changes.

1. In Razorpay Dashboard, go to **Settings → Webhooks**
2. Click **Add New Webhook**
3. Enter webhook URL: `https://yourdomain.com/webhook/razorpay`
4. Select events to subscribe to:
   - `payment.captured` - When payment is successful
   - `payment.failed` - When payment fails
   - `order.paid` - When order is paid
5. Copy the **Webhook Secret** and add it to your integration settings
6. Save the webhook

## Step 5: Test Payment Flow

### Test Mode
1. Use Razorpay test cards:
   - **Success**: `4111 1111 1111 1111`
   - **Failure**: `4000 0000 0000 0002`
   - **CVV**: Any 3 digits
   - **Expiry**: Any future date
2. Place a test order
3. Complete payment using test card
4. Verify order status updates correctly

### Live Mode
1. Switch to Live mode in settings
2. Use real payment methods
3. Test with small amounts first
4. Monitor transactions in Razorpay Dashboard

## Payment Flow

1. **Order Creation**: When customer places an order, system creates a Razorpay order
2. **Payment Page**: Customer is redirected to Razorpay payment page
3. **Payment Processing**: Customer completes payment on Razorpay
4. **Webhook Notification**: Razorpay sends webhook to your server
5. **Order Update**: System updates order status based on payment result

## Supported Payment Methods

Razorpay supports:
- Credit/Debit Cards (Visa, Mastercard, RuPay, etc.)
- UPI (Unified Payments Interface)
- Net Banking
- Wallets (Paytm, Freecharge, etc.)
- EMI (Equated Monthly Installments)
- Buy Now Pay Later (BNPL)

## Security Best Practices

1. **Never expose Key Secret**: Keep it server-side only
2. **Use HTTPS**: Always use HTTPS for webhook URLs
3. **Verify Webhook Signatures**: Always verify webhook signatures
4. **Monitor Transactions**: Regularly check Razorpay Dashboard for suspicious activity
5. **Use Test Mode**: Test thoroughly in sandbox before going live
6. **Keep Keys Secure**: Rotate keys periodically

## Troubleshooting

### Payment Not Processing
- Verify API keys are correct
- Check if mode (sandbox/live) matches your keys
- Ensure webhook URL is accessible
- Check application logs for errors

### Webhook Not Receiving Events
- Verify webhook URL is publicly accessible
- Check webhook secret is correct
- Ensure webhook is active in Razorpay Dashboard
- Check server logs for webhook requests

### Payment Verification Failed
- Verify signature calculation is correct
- Check if payment ID and order ID match
- Ensure webhook secret is configured correctly

## API Integration

For programmatic payment processing:

```php
// Create Razorpay order
$paymentService = app(\App\Services\PaymentService::class);
$result = $paymentService->createRazorpayOrder($order);

// Process payment callback
$result = $paymentService->processPayment($order, 'razorpay', [
    'razorpay_payment_id' => $paymentId,
    'razorpay_order_id' => $orderId,
    'razorpay_signature' => $signature,
]);
```

## Support

- Razorpay Documentation: https://razorpay.com/docs/
- Razorpay Support: support@razorpay.com
- Check application logs for detailed error messages

