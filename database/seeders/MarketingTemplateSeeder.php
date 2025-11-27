<?php

namespace Database\Seeders;

use App\Models\MarketingTemplate;
use Illuminate\Database\Seeder;

class MarketingTemplateSeeder extends Seeder
{
	public function run(): void
	{
		$templates = [
			// Email Templates
			[
				'name' => 'Order Confirmation Email',
				'type' => 'email',
				'subject' => 'Order Confirmation - Order #{{order_number}}',
				'content' => "Dear {{customer_name}},\n\nThank you for your order! We're excited to confirm that we've received your order #{{order_number}}.\n\nOrder Details:\n- Order Number: {{order_number}}\n- Order Date: {{order_date}}\n- Total Amount: {{order_total}}\n\nWe'll send you another email once your order has been shipped.\n\nIf you have any questions, please contact us at {{support_email}}.\n\nThank you for shopping with {{site_name}}!",
				'variables' => ['customer_name', 'order_number', 'order_date', 'order_total', 'support_email', 'site_name'],
				'is_active' => true,
			],
			[
				'name' => 'Order Shipped Email',
				'type' => 'email',
				'subject' => 'Your Order #{{order_number}} Has Been Shipped!',
				'content' => "Dear {{customer_name}},\n\nGreat news! Your order #{{order_number}} has been shipped and is on its way to you.\n\nShipping Details:\n- Order Number: {{order_number}}\n- Tracking Number: {{tracking_number}}\n- Estimated Delivery: {{estimated_delivery}}\n\nYou can track your package using the tracking number above.\n\nWe hope you love your purchase! If you have any questions, please contact us at {{support_email}}.\n\nThank you for shopping with {{site_name}}!",
				'variables' => ['customer_name', 'order_number', 'tracking_number', 'estimated_delivery', 'support_email', 'site_name'],
				'is_active' => true,
			],
			[
				'name' => 'Welcome Email',
				'type' => 'email',
				'subject' => 'Welcome to {{site_name}}!',
				'content' => "Dear {{customer_name}},\n\nWelcome to {{site_name}}! We're thrilled to have you as part of our community.\n\nAs a special welcome gift, here's a discount code for your first purchase:\nDiscount Code: {{discount_code}}\n\nStart shopping now and discover our amazing products!\n\nIf you have any questions, feel free to reach out to us at {{support_email}}.\n\nHappy shopping!\nThe {{site_name}} Team",
				'variables' => ['customer_name', 'site_name', 'discount_code', 'support_email'],
				'is_active' => true,
			],
			[
				'name' => 'Password Reset Email',
				'type' => 'email',
				'subject' => 'Reset Your Password - {{site_name}}',
				'content' => "Dear {{customer_name}},\n\nWe received a request to reset your password for your {{site_name}} account.\n\nClick the link below to reset your password:\n{{reset_link}}\n\nThis link will expire in 60 minutes. If you didn't request a password reset, please ignore this email.\n\nIf you have any questions, please contact us at {{support_email}}.\n\nBest regards,\nThe {{site_name}} Team",
				'variables' => ['customer_name', 'site_name', 'reset_link', 'support_email'],
				'is_active' => true,
			],
			[
				'name' => 'Cart Abandonment Email',
				'type' => 'email',
				'subject' => 'Complete Your Purchase - {{site_name}}',
				'content' => "Dear {{customer_name}},\n\nWe noticed you left some items in your cart!\n\n{{cart_items}}\n\nDon't miss out! Complete your purchase now and use this special discount code:\nDiscount Code: {{discount_code}}\n\nThis offer is valid for the next 24 hours. Click here to complete your order.\n\nIf you have any questions, please contact us at {{support_email}}.\n\nHappy shopping!\nThe {{site_name}} Team",
				'variables' => ['customer_name', 'cart_items', 'discount_code', 'support_email', 'site_name'],
				'is_active' => true,
			],
			
			// WhatsApp Templates
			[
				'name' => 'Order Confirmation WhatsApp',
				'type' => 'whatsapp',
				'subject' => null,
				'content' => "Hi {{customer_name}}! 👋\n\nThank you for your order! We've received your order #{{order_number}}.\n\n📦 Order Details:\n• Order #: {{order_number}}\n• Date: {{order_date}}\n• Total: {{order_total}}\n\nWe'll notify you once your order ships.\n\nQuestions? Contact us: {{support_email}}\n\nThank you for shopping with {{site_name}}! 🛍️",
				'variables' => ['customer_name', 'order_number', 'order_date', 'order_total', 'support_email', 'site_name'],
				'is_active' => true,
			],
			[
				'name' => 'Order Shipped WhatsApp',
				'type' => 'whatsapp',
				'subject' => null,
				'content' => "Hi {{customer_name}}! 🎉\n\nGreat news! Your order #{{order_number}} has been shipped!\n\n📦 Shipping Info:\n• Tracking: {{tracking_number}}\n• Delivery: {{estimated_delivery}}\n\nTrack your package using the tracking number above.\n\nQuestions? Contact: {{support_email}}\n\nThank you for shopping with {{site_name}}! 🚚",
				'variables' => ['customer_name', 'order_number', 'tracking_number', 'estimated_delivery', 'support_email', 'site_name'],
				'is_active' => true,
			],
			[
				'name' => 'Welcome WhatsApp',
				'type' => 'whatsapp',
				'subject' => null,
				'content' => "Hi {{customer_name}}! 👋\n\nWelcome to {{site_name}}! We're excited to have you.\n\n🎁 Special Welcome Gift:\nUse code: {{discount_code}}\n\nStart shopping now!\n\nQuestions? Contact: {{support_email}}\n\nHappy shopping! 🛍️\nThe {{site_name}} Team",
				'variables' => ['customer_name', 'site_name', 'discount_code', 'support_email'],
				'is_active' => true,
			],
			[
				'name' => 'Password Reset WhatsApp',
				'type' => 'whatsapp',
				'subject' => null,
				'content' => "Hi {{customer_name}},\n\nWe received a password reset request for your {{site_name}} account.\n\n🔐 Reset Link:\n{{reset_link}}\n\nLink expires in 60 minutes.\n\nDidn't request this? Please ignore this message.\n\nQuestions? Contact: {{support_email}}\n\n{{site_name}} Team",
				'variables' => ['customer_name', 'site_name', 'reset_link', 'support_email'],
				'is_active' => true,
			],
			[
				'name' => 'Cart Abandonment WhatsApp',
				'type' => 'whatsapp',
				'subject' => null,
				'content' => "Hi {{customer_name}}! 👋\n\nYou left items in your cart!\n\n{{cart_items}}\n\n🎁 Special Offer:\nUse code: {{discount_code}}\n\nValid for 24 hours only!\n\nComplete your purchase now.\n\nQuestions? Contact: {{support_email}}\n\n{{site_name}} Team 🛍️",
				'variables' => ['customer_name', 'cart_items', 'discount_code', 'support_email', 'site_name'],
				'is_active' => true,
			],
		];

		foreach ($templates as $template) {
			MarketingTemplate::updateOrCreate(
				[
					'name' => $template['name'],
					'type' => $template['type'],
				],
				$template
			);
		}
	}
}

