<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin',
            'password' => bcrypt('123'),
            'role' => 'admin',
            'status' => 'on',
        ]);

        // Create seller user
        \App\Models\User::factory()->create([
            'name' => 'Seller',
            'email' => 'seller@seller',
            'password' => bcrypt('123'),
            'role' => 'seller',
            'status' => 'on',
        ]);

        // Create Buyer user
        \App\Models\User::factory()->create([
            'name' => 'Buyer',
            'email' => 'rafisaifullah.u@gmail.com',
            'password' => bcrypt('123'),
            'role' => 'buyer',
            'alamat' => 'Jl. Buyer',
            'no_hp' => '08123456789',
            'status' => 'on',
        ]);

        // Create 1000 regular users
        \App\Models\User::factory(200)->create();

        // Create barang
        \App\Models\Barang::factory(200)->create();


        // // Create pesanan dengan status acak antara 'co' dan 'pickup'
        \App\Models\Pesanan::factory(500)->create();


        // // Create pesanan detail dengan pesanan_id (yang sudah terhubung ke pesanan)
        \App\Models\PesananDetail::factory(1000)->create();

        // // Create pesanan detail tanpa pesanan_id (null)
        \App\Models\PesananDetail::factory(200)->withoutPesanan()->create();
    }
}
