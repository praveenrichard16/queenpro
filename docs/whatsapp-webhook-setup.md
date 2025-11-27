# WhatsApp Webhook Setup Guide

This guide explains how to set up WhatsApp webhooks to receive real-time events from Meta's WhatsApp Business API.

## Overview

Webhooks allow Meta to send real-time notifications to your application about:
- Incoming messages from customers
- Message delivery status updates
- Template status changes (approved, rejected, etc.)
- Template quality rating updates

## Prerequisites

1. WhatsApp Business API configured in Integration Settings
2. Access to Meta for Developers (https://developers.facebook.com)
3. Your application must be accessible via HTTPS (required by Meta)

## Step 1: Generate a Webhook Verify Token

1. Go to **Admin Panel → Integrations → WhatsApp Meta**
2. In the **Webhook Verify Token** field, enter a secure random string
   - Example: `my_secure_webhook_token_2024_xyz123`
   - This token will be used to verify webhook requests from Meta
3. **Save** the settings

## Step 2: Configure Webhook in Meta Business Manager

1. Go to [Meta for Developers](https://developers.facebook.com)
2. Select your **WhatsApp Business App**
3. Navigate to **WhatsApp → Configuration** in the left sidebar
4. Scroll down to the **Webhook** section
5. Click **Edit** or **Set up webhooks**

### Webhook Configuration Details

- **Webhook URL**: `https://yourdomain.com/webhook/whatsapp`
  - Replace `yourdomain.com` with your actual domain
  - Example: `https://example.com/webhook/whatsapp`
  
- **Verify Token**: Enter the same token you set in Step 1
  - This must match exactly (case-sensitive)

- **Webhook Fields**: Subscribe to the following fields:
  - ✅ `messages` - Receive incoming messages and message status updates
  - ✅ `message_template_status_update` - Receive template approval/rejection notifications
  - ✅ `message_template_quality_update` - Receive template quality rating updates

6. Click **Verify and Save**

## Step 3: Verify Webhook Connection

After clicking "Verify and Save" in Meta:

1. Meta will send a GET request to your webhook URL
2. Your application will verify the token
3. If successful, Meta will show "Webhook verified" status
4. You should see a success message in your application logs

## Step 4: Test Webhook

### Test Incoming Messages

1. Send a test message to your WhatsApp Business number
2. Check your application logs for the webhook event
3. The webhook should receive the message data

### Test Template Status Updates

1. Create or update a template in Meta Business Manager
2. When the template is approved/rejected, you'll receive a webhook notification
3. Check your application logs for the status update

## Webhook Events

### 1. Incoming Messages

When a customer sends a message, you'll receive:

```json
{
  "object": "whatsapp_business_account",
  "entry": [{
    "id": "WHATSAPP_BUSINESS_ACCOUNT_ID",
    "changes": [{
      "value": {
        "messaging_product": "whatsapp",
        "metadata": {
          "display_phone_number": "+1234567890",
          "phone_number_id": "PHONE_NUMBER_ID"
        },
        "messages": [{
          "from": "CUSTOMER_PHONE_NUMBER",
          "id": "MESSAGE_ID",
          "timestamp": "1234567890",
          "type": "text",
          "text": {
            "body": "Hello, I need help"
          }
        }]
      },
      "field": "messages"
    }]
  }]
}
```

### 2. Message Status Updates

When a message status changes (sent, delivered, read), you'll receive:

```json
{
  "object": "whatsapp_business_account",
  "entry": [{
    "changes": [{
      "value": {
        "statuses": [{
          "id": "MESSAGE_ID",
          "status": "delivered",
          "timestamp": "1234567890",
          "recipient_id": "CUSTOMER_PHONE_NUMBER"
        }]
      },
      "field": "messages"
    }]
  }]
}
```

### 3. Template Status Updates

When a template is approved or rejected:

```json
{
  "object": "whatsapp_business_account",
  "entry": [{
    "changes": [{
      "value": {
        "event": "APPROVED",
        "message_template_id": "TEMPLATE_ID",
        "message_template_name": "template_name",
        "message_template_language": "en",
        "reason": "Template approved"
      },
      "field": "message_template_status_update"
    }]
  }]
}
```

## Troubleshooting

### Webhook Verification Fails

**Problem**: Meta shows "Webhook verification failed"

**Solutions**:
1. Check that the Verify Token matches exactly (case-sensitive)
2. Ensure your webhook URL is accessible via HTTPS
3. Check application logs for error messages
4. Verify the webhook route is not blocked by firewall or middleware

### Webhook Not Receiving Events

**Problem**: Webhook is verified but not receiving events

**Solutions**:
1. Verify you've subscribed to the correct webhook fields
2. Check that your application is accessible from the internet
3. Review application logs for incoming requests
4. Ensure your webhook endpoint returns HTTP 200 status

### SSL Certificate Issues

**Problem**: Meta cannot verify your webhook due to SSL issues

**Solutions**:
1. Ensure your domain has a valid SSL certificate
2. Use a trusted certificate authority (Let's Encrypt, etc.)
3. Test your webhook URL in a browser - it should show a valid certificate

## Security Considerations

1. **Verify Token**: Use a strong, random token (at least 32 characters)
2. **HTTPS Only**: Meta requires HTTPS for webhooks
3. **IP Whitelisting**: Consider whitelisting Meta's IP ranges (optional)
4. **Rate Limiting**: Implement rate limiting to prevent abuse
5. **Logging**: Log all webhook events for debugging and security

## Webhook URL Format

Your webhook URL should be:
```
https://yourdomain.com/webhook/whatsapp
```

For local development, you can use tools like:
- ngrok: `ngrok http 8000`
- Cloudflare Tunnel
- LocalTunnel

Then use the provided HTTPS URL as your webhook URL.

## Support

If you encounter issues:
1. Check application logs: `storage/logs/laravel.log`
2. Review Meta's webhook documentation
3. Test webhook endpoint manually using curl or Postman
4. Contact support with error logs and webhook configuration details

