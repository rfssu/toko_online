<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PesananDetail>
 */
class PesananDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'barang_id' => Barang::inRandomOrder()->first()->id ?? Barang::factory(),
            'pesanan_id' => $this->faker->optional(0.7)->randomElement(
                Pesanan::pluck('id')->toArray()
            ), // 70% ada pesanan_id, 30% null
            'harga' => $this->faker->numberBetween(10000, 500000),
            'jumlah' => $this->faker->numberBetween(1, 10),
        ];
    }

    /**
     * State untuk pesanan detail tanpa pesanan_id (null)
     */
    public function withoutPesanan(): static
    {
        return $this->state(fn(array $attributes) => [
            'pesanan_id' => null,
        ]);
    }
}
