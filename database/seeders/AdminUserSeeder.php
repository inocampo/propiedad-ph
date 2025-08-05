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
            'name' => 'Administrador',
            'email' => 'admin@gualanday.com',
            'password' => Hash::make('password123'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);
    }
}