# Drip Campaigns Setup Guide

## Overview
Drip campaigns allow you to send automated, multi-step email and WhatsApp messages to customers, enquiries, and leads. Each campaign can have multiple steps with configurable delays between them.

## Features
- **Multi-step campaigns**: Create campaigns with multiple steps, each with its own delay
- **Multiple channels**: Send via Email, WhatsApp, or both
- **Trigger types**: 
  - **New Enquiry**: Automatically triggered when a new enquiry is created
  - **New Lead**: Automatically triggered when a new lead is created
  - **Manual**: Trigger campaigns manually for selected recipients
- **Recipient types**: Support for Customers, Enquiries, and Leads
- **Template-based**: Use pre-configured email and WhatsApp templates
- **Scheduled processing**: Campaigns are processed automatically via scheduled tasks

## Prerequisites
- Email integration configured (SMTP)
- WhatsApp integration configured (Meta WhatsApp Cloud API)
- Marketing templates created for email and/or WhatsApp

## Step 1: Create Marketing Templates

Before creating drip campaigns, ensure you have templates ready:

1. Navigate to **Admin Panel → Marketing → Templates**
2. Create templates for:
   - Email templates (for email campaigns)
   - WhatsApp templates (for WhatsApp campaigns)
3. Templates should include variables like `{{customer_name}}`, `{{order_number}}`, etc.

## Step 2: Create a Drip Campaign

1. Navigate to **Admin Panel → Marketing → Drip Campaigns**
2. Click **Create Drip Campaign**
3. Fill in campaign details:
   - **Campaign Name**: A descriptive name for your campaign
   - **Description**: Optional description
   - **Trigger Type**: 
     - `New Enquiry` - Automatically start when enquiry is created
     - `New Lead` - Automatically start when lead is created
     - `Manual` - Trigger manually from the trigger page
   - **Channel**: Email, WhatsApp, or Both
   - **Status**: Active/Inactive

## Step 3: Add Campaign Steps

1. Click **Add Step** to add a new step
2. For each step, configure:
   - **Step Number**: Sequential step number (1, 2, 3, etc.)
   - **Delay (Hours)**: Hours to wait after previous step before sending this step
     - For the first step, delay is typically 0 (send immediately)
   - **Template**: Select the email or WhatsApp template to use
   - **Channel**: Email or WhatsApp (must match campaign channel)
   - **Status**: Active/Inactive

### Example Campaign Flow

**Welcome Campaign (3 steps):**
- Step 1: Welcome email (Delay: 0 hours) - Send immediately
- Step 2: Product recommendations (Delay: 24 hours) - Send 1 day later
- Step 3: Special offer (Delay: 72 hours) - Send 3 days later

## Step 4: Trigger Manual Campaigns

For campaigns with trigger type "Manual":

1. Navigate to **Admin Panel → Marketing → Drip Campaigns → Trigger Campaign**
2. Select the campaign from the dropdown
3. Choose recipient type (Customers, Enquiries, or Leads)
4. Search and select recipients
5. Click **Trigger Campaign for Selected Recipients**

## Step 5: Monitor Campaign Progress

1. View campaign details to see:
   - Total recipients
   - Active recipients
   - Completed campaigns
   - Failed campaigns

2. Check individual recipient status:
   - Current step
   - Last sent date
   - Next send date
   - Status (pending, in_progress, completed, paused, cancelled, failed)

## Campaign Processing

Campaigns are processed automatically via a scheduled task that runs every 5 minutes. The system:
1. Checks for recipients with `next_send_at` in the past
2. Sends the current step's message
3. Updates the recipient to the next step
4. Calculates the next send time based on step delay

## Troubleshooting

### Campaigns Not Sending
- Verify email/WhatsApp integration is configured correctly
- Check templates are active and approved (for WhatsApp)
- Ensure scheduled task is running: `php artisan schedule:run`
- Check application logs for errors

### Recipients Not Receiving Messages
- Verify recipient email/phone number is valid
- Check phone numbers include country code (e.g., +91 for India)
- Ensure templates are properly configured
- Check spam/junk folders for emails

### Campaign Stuck on a Step
- Check if template is active
- Verify channel configuration matches template type
- Review error logs for specific issues
- Manually trigger the next step if needed

## Best Practices

1. **Test campaigns**: Always test with a small group before full deployment
2. **Reasonable delays**: Don't send too many messages too quickly
3. **Relevant content**: Ensure each step provides value to the recipient
4. **Monitor performance**: Track open rates, click rates, and conversions
5. **Update templates**: Keep templates fresh and relevant
6. **Respect preferences**: Allow recipients to unsubscribe

## API Access

Drip campaigns can also be managed via API:
- Create campaigns programmatically
- Trigger campaigns via API
- Monitor campaign status
- View recipient progress

See API documentation for details.

