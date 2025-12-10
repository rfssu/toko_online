<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function Symfony\Component\String\b;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin',
            'password' => bcrypt('123'),
            'role' => 'admin',
            'status' => 'on',
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Seller',
            'email' => 'seller@seller',
            'password' => bcrypt('123'),
            'role' => 'seller',
            'status' => 'on',
        ]);
        \App\Models\User::factory(1000)->create();
    }
}
