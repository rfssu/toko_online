<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barang>
 */
class BarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kategori = ['Elektronik', 'Pakaian', 'Makanan', 'Minuman', 'Alat Tulis', 'Olahraga', 'Kesehatan', 'Kecantikan'];
        $produk = [
            'Elektronik' => ['Laptop', 'Mouse', 'Keyboard', 'Monitor', 'Headset', 'Speaker', 'Webcam'],
            'Pakaian' => ['Kaos', 'Kemeja', 'Celana', 'Jaket', 'Sweater', 'Dress', 'Rok'],
            'Makanan' => ['Snack', 'Biskuit', 'Coklat', 'Permen', 'Keripik', 'Kue', 'Roti'],
            'Minuman' => ['Teh', 'Kopi', 'Jus', 'Susu', 'Soft Drink', 'Air Mineral', 'Energy Drink'],
            'Alat Tulis' => ['Pulpen', 'Pensil', 'Buku', 'Penghapus', 'Penggaris', 'Spidol', 'Stabilo'],
            'Olahraga' => ['Bola', 'Raket', 'Matras', 'Dumbbell', 'Sepatu Lari', 'Jersey', 'Tas Gym'],
            'Kesehatan' => ['Vitamin', 'Masker', 'Hand Sanitizer', 'Obat', 'Plester', 'Termometer', 'Salep'],
            'Kecantikan' => ['Skincare', 'Makeup', 'Parfum', 'Shampo', 'Sabun', 'Lotion', 'Serum'],
        ];

        $selectedKategori = $this->faker->randomElement($kategori);
        $selectedProduk = $this->faker->randomElement($produk[$selectedKategori]);

        return [
            'nama_barang' => $selectedProduk . ' ' . $selectedKategori . ' ' . $this->faker->word(),
            'harga' => $this->faker->numberBetween(1, 20) * 5000, // kelipatan 5000
            'stok' => $this->faker->numberBetween(0, 200),
            'keterangan' => $this->faker->optional(0.7)->sentence(),
        ];
    }
}
