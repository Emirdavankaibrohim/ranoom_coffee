<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Admin System',
                'password' => Hash::make('Password123'),
                'role'     => 'admin',
                'phone'    => '081234567890',
                'address'  => 'Admin HQ, Jakarta',
                'provider' => 'simple',
                'status'   => 'Active',
            ]
        );

        // Chef
        User::updateOrCreate(
            ['email' => 'chef@gmail.com'],
            [
                'name'     => 'Chef Juna',
                'password' => Hash::make('Password123'),
                'role'     => 'chef',
                'phone'    => '081234567891',
                'address'  => 'Kitchen Dept',
                'provider' => 'simple',
                'status'   => 'Active',
            ]
        );

        // Cashier
        User::updateOrCreate(
            ['email' => 'cashier@gmail.com'],
            [
                'name'     => 'Cashier Siska',
                'password' => Hash::make('Password123'),
                'role'     => 'cashier',
                'phone'    => '081234567892',
                'address'  => 'Front Desk',
                'provider' => 'simple',
                'status'   => 'Active',
            ]
        );

        // Regular User
        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name'     => 'Customer Budi',
                'password' => Hash::make('Password123'),
                'role'     => 'user',
                'phone'    => '081234567893',
                'address'  => 'Jl. Sudirman No. 1, Jakarta',
                'provider' => 'simple',
                'status'   => 'Active',
            ]
        );
    }
}
