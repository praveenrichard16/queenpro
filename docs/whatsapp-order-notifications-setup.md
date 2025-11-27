# WhatsApp Order Notifications Setup Guide

## Overview
This guide will help you set up WhatsApp order notifications for your e-commerce store. When customers place orders or order statuses are updated, they will receive automated WhatsApp messages.

## Prerequisites
- Meta Business Account
- WhatsApp Business API access
- Approved WhatsApp Business Account
- Meta approved message templates

## Step 1: Create Meta Business Account
1. Go to [Meta Business Suite](https://business.facebook.com/)
2. Create or access your Business Account
3. Add your WhatsApp Business Account

## Step 2: Get API Credentials
1. Go to [Meta for Developers](https://developers.facebook.com/)
2. Create a new app or select existing app
3. Add "WhatsApp" product to your app
4. Get the following credentials:
   - **Phone Number ID**: Found in WhatsApp → API Setup
   - **WhatsApp Business Account ID**: Found in WhatsApp → API Setup
   - **Business Manager ID**: Found in Business Settings
   - **App ID**: Found in App Settings → Basic
   - **App Secret**: Found in App Settings → Basic
   - **Permanent Access Token**: Generate in WhatsApp → API Setup

## Step 3: Configure in Admin Panel
1. Navigate to **Admin Panel → Integrations → WhatsApp**
2. Enable "Meta WhatsApp Cloud API"
3. Fill in all the credentials from Step 2:
   - Phone Number ID
   - WhatsApp Business Account ID
   - Business Manager ID
   - App ID
   - App Secret
   - Permanent Access Token
   - Webhook Verify Token (create a random secure string)
   - API Version (default: v19.0)
   - Language Code (default: en)
4. Click "Save Meta Settings"
5. Click "Send Test Message" to verify connection

## Step 4: Create Message Templates
You need to create approved templates in Meta Business Manager:

### Template 1: Order Created
1. Go to Meta Business Manager → WhatsApp → Message Templates
2. Click "Create Template"
3. Template Name: `order_created` (or your preferred name)
4. Category: UTILITY
5. Language: English
6. Add body text with variables:
   ```
   Hello {{1}}, your order {{2}} has been confirmed. Total amount: {{3}}. Thank you for your purchase!
   ```
7. Variables:
   - {{1}} = Customer Name
   - {{2}} = Order Number
   - {{3}} = Order Total
8. Submit for approval (usually takes 24-48 hours)

### Template 2: Order Status Updated
1. Create another template named `order_status_updated`
2. Category: UTILITY
3. Body text:
   ```
   Hello {{1}}, your order {{2}} status has been updated from {{3}} to {{4}}. Track your order on our website.
   ```
4. Variables:
   - {{1}} = Customer Name
   - {{2}} = Order Number
   - {{3}} = Old Status
   - {{4}} = New Status
5. Submit for approval

## Step 5: Configure Template Names
1. In Admin Panel → Integrations → WhatsApp
2. Enter template names:
   - **Order Created Template Name**: `order_created` (or your template name)
   - **Order Status Updated Template Name**: `order_status_updated` (or your template name)
3. Save settings

## Step 6: Set Up Webhook (Optional but Recommended)
1. In Meta for Developers → WhatsApp → Configuration
2. Add webhook URL: `https://yourdomain.com/webhook/whatsapp`
3. Verify Token: Use the same token you set in admin panel
4. Subscribe to: `messages` events

## Step 7: Test Order Notifications
1. Place a test order in your store
2. Check if WhatsApp message is sent to customer
3. Update order status and verify status update message

## Troubleshooting

### Messages Not Sending
- Verify all credentials are correct
- Check if templates are approved in Meta
- Ensure customer phone number is in international format (e.g., +971501234567)
- Check application logs for error messages

### Template Not Found
- Verify template name matches exactly (case-sensitive)
- Ensure template is approved and active
- Check template language matches your configuration

### Authentication Errors
- Verify access token is permanent (not temporary)
- Check token has necessary permissions
- Regenerate token if expired

## Template Variables Reference

### Order Created Template
- `{{1}}` - Customer Name
- `{{2}}` - Order Number
- `{{3}}` - Order Total (formatted)

### Order Status Updated Template
- `{{1}}` - Customer Name
- `{{2}}` - Order Number
- `{{3}}` - Previous Status
- `{{4}}` - New Status

## Important Notes
- Templates must be approved by Meta before use
- Phone numbers must be in international format
- Rate limits apply (check Meta documentation)
- Templates cannot be used for marketing (only utility/transactional)
- Customer must have opted in to receive messages

## Support
For issues with:
- **Meta API**: Contact Meta Business Support
- **Integration**: Check application logs in `storage/logs/laravel.log`
- **Templates**: Verify in Meta Business Manager → WhatsApp → Message Templates

