<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::table('categories')->insert([
            ['name' => 'Nasi Goreng', 'type' => 'Makanan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kopi', 'type' => 'Minuman', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Snack', 'type' => 'Makanan Ringan', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
