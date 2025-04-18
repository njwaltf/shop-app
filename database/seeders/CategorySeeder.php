<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Food & Beverages', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Stationery', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Electronics', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cleaning Supplies', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Personal Care', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
