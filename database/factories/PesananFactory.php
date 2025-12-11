<?php

namespace Database\Factories;

use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pesanan>
 */
class PesananFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode' => 'PO-' . strtoupper($this->faker->unique()->bothify('####??##')),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'tanggal_pickup' => $this->faker->dateTimeBetween('now', '+30 days'),
            'status' => $this->faker->randomElement(['co', 'pickup']),
        ];
    }
}
