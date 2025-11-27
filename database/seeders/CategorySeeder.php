<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$categories = [
			[
				'name' => 'Electronics',
				'description' => 'Latest electronic devices and gadgets',
				'slug' => 'electronics',
				'is_active' => true,
			],
			[
				'name' => 'Clothing',
				'description' => 'Fashion and apparel for all ages',
				'slug' => 'clothing',
				'is_active' => true,
			],
			[
				'name' => 'Home & Garden',
				'description' => 'Everything for your home and garden',
				'slug' => 'home-garden',
				'is_active' => true,
			],
			[
				'name' => 'Sports',
				'description' => 'Sports equipment and accessories',
				'slug' => 'sports',
				'is_active' => true,
			],
			[
				'name' => 'Books',
				'description' => 'Books and educational materials',
				'slug' => 'books',
				'is_active' => true,
			],
		];

		foreach ($categories as $data) {
			Category::updateOrCreate(
				['slug' => $data['slug']],
				[
					'name' => $data['name'],
					'description' => $data['description'] ?? null,
					'is_active' => $data['is_active'] ?? true,
				]
			);
		}
	}
}
