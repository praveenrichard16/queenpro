# WhatsApp Catalog and Direct Checkout Setup Guide

## Overview
This guide will help you set up WhatsApp Business Catalog and enable direct checkout from WhatsApp. This allows customers to browse your products in WhatsApp and complete purchases directly.

## Prerequisites
- Meta Business Account with WhatsApp Business API access
- Products added to your store
- WhatsApp integration configured (see WhatsApp Order Notifications Setup)

## Step 1: Verify WhatsApp Integration
1. Ensure WhatsApp is configured in **Admin Panel → Integrations → WhatsApp**
2. Verify all credentials are correct:
   - Phone Number ID
   - WhatsApp Business Account ID
   - Access Token
   - API Version

## Step 2: Sync Products to WhatsApp Catalog

### Individual Product Sync
1. Navigate to **Admin Panel → Integrations → WhatsApp Catalog**
2. Find the product you want to sync
3. Click **Sync** button next to the product
4. Wait for sync confirmation

### Bulk Product Sync
1. In WhatsApp Catalog page, select multiple products using checkboxes
2. Click **Bulk Sync** button
3. Confirm the sync operation
4. Monitor sync status in the table

## Step 3: Product Requirements for WhatsApp Catalog
Products must meet these requirements to sync successfully:

- **Name**: Required, max 100 characters
- **Description**: Required, max 4096 characters (HTML will be stripped)
- **Price**: Required, must be greater than 0
- **Image**: Required, must be publicly accessible URL
- **Stock Status**: Required (in stock or out of stock)

### Image Requirements
- Format: JPG, PNG, or WebP
- Minimum size: 500x500 pixels
- Maximum size: 8MB
- Must be publicly accessible (not behind authentication)

## Step 4: Get Catalog Link
1. In WhatsApp Catalog page, click **Get Catalog Link**
2. Copy the generated link
3. Share this link with customers via WhatsApp or other channels
4. Customers can click the link to view your catalog in WhatsApp

## Step 5: Direct Checkout Flow

### How It Works
1. Customer clicks product link in WhatsApp
2. They are redirected to your website checkout page
3. Product is automatically added to cart
4. Customer completes checkout on your website

### Product Links
Each synced product gets a WhatsApp link that:
- Opens WhatsApp with product information
- Allows customer to message you about the product
- Can be shared via WhatsApp or other channels

## Step 6: Testing

### Test Product Sync
1. Create a test product with all required fields
2. Ensure product has an image
3. Sync the product to WhatsApp
4. Verify sync status shows "Synced"

### Test Catalog Link
1. Generate catalog link
2. Open link in WhatsApp
3. Verify catalog displays correctly
4. Test product browsing

### Test Checkout Flow
1. Click a product link from WhatsApp
2. Verify product is added to cart
3. Complete checkout process
4. Verify order is created successfully

## Troubleshooting

### Product Sync Fails
**Error: "WhatsApp is not configured"**
- Verify WhatsApp integration settings
- Check all credentials are correct
- Ensure access token is valid

**Error: "Failed to create catalog"**
- Verify WhatsApp Business Account ID is correct
- Check API permissions
- Ensure catalog creation is enabled in Meta Business Manager

**Error: "Image URL not accessible"**
- Ensure product images are publicly accessible
- Check image URLs are full URLs (not relative paths)
- Verify images meet size and format requirements

### Catalog Link Not Working
- Verify WhatsApp Phone Number ID is correct
- Check if catalog has products synced
- Ensure WhatsApp integration is enabled

### Checkout Not Working
- Verify product is synced to WhatsApp
- Check product is active and in stock
- Ensure user is logged in (or will be redirected to login)

## Best Practices

1. **Regular Sync**: Sync products regularly, especially after:
   - Price changes
   - Stock updates
   - Product information changes
   - New products added

2. **Image Quality**: Use high-quality product images
   - Clear, well-lit photos
   - Multiple angles if possible
   - Consistent image style

3. **Product Descriptions**: Write clear, concise descriptions
   - Highlight key features
   - Include important specifications
   - Keep within character limits

4. **Stock Management**: Keep stock status updated
   - Sync products after stock changes
   - Mark out-of-stock products appropriately
   - Update availability regularly

## API Limitations

- **Rate Limits**: Meta API has rate limits
  - Bulk syncs may take time
  - Don't sync too frequently
  - Monitor API usage

- **Catalog Size**: 
  - Maximum products per catalog: Varies by plan
  - Check Meta Business Manager for limits

- **Sync Frequency**:
  - Don't sync same product multiple times rapidly
  - Wait for sync to complete before re-syncing

## Support

For issues with:
- **Meta API**: Contact Meta Business Support
- **Integration**: Check application logs in `storage/logs/laravel.log`
- **Product Sync**: Verify product meets all requirements
- **Catalog Display**: Check Meta Business Manager → WhatsApp → Catalog

## Additional Resources

- [Meta WhatsApp Business API Documentation](https://developers.facebook.com/docs/whatsapp)
- [WhatsApp Catalog API Reference](https://developers.facebook.com/docs/whatsapp/cloud-api/guides/commerce-platform)
- [Product Catalog Best Practices](https://developers.facebook.com/docs/whatsapp/cloud-api/guides/commerce-platform#best-practices)

