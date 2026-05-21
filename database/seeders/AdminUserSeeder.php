<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@mcd.com'],
            [
                'name' => 'Admin McD',
                'email' => 'admin@mcd.com',
                'password' => Hash::make('admin123'),
                'phone' => '081234567890',
                'address' => 'Jakarta, Indonesia',
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create a regular user for testing
        User::updateOrCreate(
            ['email' => 'user@mcd.com'],
            [
                'name' => 'Test User',
                'email' => 'user@mcd.com',
                'password' => Hash::make('user123'),
                'phone' => '081234567891',
                'address' => 'Jakarta, Indonesia',
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );
    }
}