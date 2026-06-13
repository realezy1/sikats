<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::table('users')->insert([
            [
                'name' => 'Admin SiKats',
                'email' => 'admin@example.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role_id' => 1,
                'mobile_number' => '081234567890',
                'address' => 'Jl. Admin',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kasir 1',
                'email' => 'kasir1@example.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role_id' => 2,
                'mobile_number' => '081234567891',
                'address' => 'Jl. Kasir',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dapur 1',
                'email' => 'dapur1@example.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role_id' => 3, // Changed from 4 to 3
                'mobile_number' => '081234567893',
                'address' => 'Jl. Dapur',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
