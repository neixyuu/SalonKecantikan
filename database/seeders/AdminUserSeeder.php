<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'           => 'Admin Salon',
            'email'          => 'admin@salon.com',
            'password'       => Hash::make('password123'),
            'role'           => 'admin',
            'account_status' => 'verified',
            'phone'          => '081234567890',
        ]);
    }
}
