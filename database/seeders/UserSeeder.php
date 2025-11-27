<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
	public function run(): void
	{
		// Admin user
		User::updateOrCreate(
			['email' => 'admin@example.com'],
			[
				'name' => 'Admin',
				'password' => Hash::make('password'),
				'is_staff' => false,
				'is_admin' => true,
				'is_super_admin' => true,
			]
		);

		// Customer user
		User::updateOrCreate(
			['email' => 'customer@example.com'],
			[
				'name' => 'Customer',
				'password' => Hash::make('password'),
				'is_staff' => false,
				'is_admin' => false,
				'is_super_admin' => false,
			]
		);
	}
}
