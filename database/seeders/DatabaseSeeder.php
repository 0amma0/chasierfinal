<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Toko',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
                'phone' => '081234567890',
            ]
        );

        // Kasir
        User::updateOrCreate(
            ['email' => 'kasir@gmail.com'],
            [
                'name' => 'Kasir Utama',
                'password' => Hash::make('password'),
                'role' => 'kasir',
                'is_active' => true,
                'phone' => '081234567891',
            ]
        );

        // Default Categories
        Category::updateOrCreate(['name' => 'Makanan']);
        Category::updateOrCreate(['name' => 'Minuman']);
        Category::updateOrCreate(['name' => 'Snack']);
        Category::updateOrCreate(['name' => 'Lainnya']);

        // Default Members
        Member::updateOrCreate(
            ['phone' => '081111222333'],
            [
                'name' => 'Member Reguler',
                'discount_percent' => 10.00,
                'is_active' => true,
            ]
        );

        Member::updateOrCreate(
            ['phone' => '082222333444'],
            [
                'name' => 'Member Gold',
                'discount_percent' => 15.00,
                'is_active' => true,
            ]
        );

        Member::updateOrCreate(
            ['phone' => '083333444555'],
            [
                'name' => 'Member VIP',
                'discount_percent' => 20.00,
                'is_active' => true,
            ]
        );

        Member::updateOrCreate(
            ['phone' => '084444555666'],
            [
                'name' => 'Member Platinum',
                'discount_percent' => 25.00,
                'is_active' => true,
            ]
        );
    }
}
