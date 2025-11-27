<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$categoryBySlug = Category::pluck('id', 'slug');

		$products = [
			[
				'name' => 'Smartphone Pro Max',
				'description' => 'Latest smartphone with advanced features and high-quality camera',
				'price' => 999.99,
				'category_slug' => 'electronics',
				'image' => 'https://via.placeholder.com/400x400/007bff/ffffff?text=Smartphone',
				'stock_quantity' => 50,
				'is_active' => true,
				'slug' => 'smartphone-pro-max',
			],
			[
				'name' => 'Wireless Headphones',
				'description' => 'Premium wireless headphones with noise cancellation',
				'price' => 299.99,
				'category_slug' => 'electronics',
				'image' => 'https://via.placeholder.com/400x400/28a745/ffffff?text=Headphones',
				'stock_quantity' => 30,
				'is_active' => true,
				'slug' => 'wireless-headphones',
			],
			[
				'name' => 'Laptop Ultra',
				'description' => 'High-performance laptop for work and gaming',
				'price' => 1299.99,
				'category_slug' => 'electronics',
				'image' => 'https://via.placeholder.com/400x400/6f42c1/ffffff?text=Laptop',
				'stock_quantity' => 20,
				'is_active' => true,
				'slug' => 'laptop-ultra',
			],
			[
				'name' => 'Cotton T-Shirt',
				'description' => 'Comfortable cotton t-shirt in various colors',
				'price' => 24.99,
				'category_slug' => 'clothing',
				'image' => 'https://via.placeholder.com/400x400/dc3545/ffffff?text=T-Shirt',
				'stock_quantity' => 100,
				'is_active' => true,
				'slug' => 'cotton-t-shirt',
			],
			[
				'name' => 'Denim Jeans',
				'description' => 'Classic denim jeans with modern fit',
				'price' => 79.99,
				'category_slug' => 'clothing',
				'image' => 'https://via.placeholder.com/400x400/17a2b8/ffffff?text=Jeans',
				'stock_quantity' => 75,
				'is_active' => true,
				'slug' => 'denim-jeans',
			],
			[
				'name' => 'Coffee Maker',
				'description' => 'Automatic coffee maker for perfect morning brew',
				'price' => 149.99,
				'category_slug' => 'home-garden',
				'image' => 'https://via.placeholder.com/400x400/fd7e14/ffffff?text=Coffee+Maker',
				'stock_quantity' => 25,
				'is_active' => true,
				'slug' => 'coffee-maker',
			],
			[
				'name' => 'Garden Tools Set',
				'description' => 'Complete set of gardening tools for your garden',
				'price' => 89.99,
				'category_slug' => 'home-garden',
				'image' => 'https://via.placeholder.com/400x400/20c997/ffffff?text=Garden+Tools',
				'stock_quantity' => 40,
				'is_active' => true,
				'slug' => 'garden-tools-set',
			],
			[
				'name' => 'Yoga Mat',
				'description' => 'Premium yoga mat for comfortable practice',
				'price' => 49.99,
				'category_slug' => 'sports',
				'image' => 'https://via.placeholder.com/400x400/e83e8c/ffffff?text=Yoga+Mat',
				'stock_quantity' => 60,
				'is_active' => true,
				'slug' => 'yoga-mat',
			],
			[
				'name' => 'Running Shoes',
				'description' => 'Comfortable running shoes for all terrains',
				'price' => 129.99,
				'category_slug' => 'sports',
				'image' => 'https://via.placeholder.com/400x400/6c757d/ffffff?text=Running+Shoes',
				'stock_quantity' => 45,
				'is_active' => true,
				'slug' => 'running-shoes',
			],
			[
				'name' => 'Programming Guide',
				'description' => 'Complete guide to modern programming languages',
				'price' => 39.99,
				'category_slug' => 'books',
				'image' => 'https://via.placeholder.com/400x400/ffc107/000000?text=Programming+Book',
				'stock_quantity' => 80,
				'is_active' => true,
				'slug' => 'programming-guide',
			],
			[
				'name' => 'Cookbook Collection',
				'description' => 'Delicious recipes from around the world',
				'price' => 29.99,
				'category_slug' => 'books',
				'image' => 'https://via.placeholder.com/400x400/28a745/ffffff?text=Cookbook',
				'stock_quantity' => 35,
				'is_active' => true,
				'slug' => 'cookbook-collection',
			],
		];

		foreach ($products as $data) {
			$categoryId = $categoryBySlug[$data['category_slug']] ?? null;
			if (!$categoryId) {
				continue;
			}

			Product::updateOrCreate(
				['slug' => $data['slug']],
				[
					'name' => $data['name'],
					'description' => $data['description'] ?? null,
					'price' => $data['price'],
					'category_id' => $categoryId,
					'image' => $data['image'] ?? null,
					'stock_quantity' => $data['stock_quantity'] ?? 0,
					'is_active' => $data['is_active'] ?? true,
				]
			);
		}
	}
}
