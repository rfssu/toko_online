<?php

namespace App\Imports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Log;

class BarangImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnError,
    SkipsOnFailure,
    WithBatchInserts,
    WithChunkReading
{
    public $imported = 0;
    public $updated = 0;
    public $errors = [];

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Check if product exists by nama_barang
        $existingProduct = Barang::where('nama_barang', $row['nama_barang'])->first();

        if ($existingProduct) {
            // UPDATE: Add to existing stock
            $existingProduct->stok += (int) $row['stok'];
            $existingProduct->harga = (int) $row['harga'];
            if (!empty($row['keterangan'])) {
                $existingProduct->keterangan = $row['keterangan'];
            }
            $existingProduct->save();

            $this->updated++;
            return null; // Don't create new, just updated
        }

        // CREATE: New product
        $this->imported++;
        return new Barang([
            'nama_barang' => $row['nama_barang'],
            'harga' => (int) $row['harga'],
            'stok' => (int) $row['stok'],
            'keterangan' => $row['keterangan'] ?? null,
        ]);
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ];
    }

    /**
     * Custom messages
     */
    public function customValidationMessages()
    {
        return [
            'nama_barang.required' => 'Nama barang wajib diisi',
            'harga.required' => 'Harga wajib diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'stok.required' => 'Stok wajib diisi',
            'stok.integer' => 'Stok harus berupa angka bulat',
        ];
    }

    /**
     * Handle errors
     */
    public function onError(\Throwable $e)
    {
        Log::error('Import Error: ' . $e->getMessage());
        $this->errors[] = $e->getMessage();
    }

    /**
     * Handle validation failures
     */
    public function onFailure(\Maatwebsite\Excel\Validators\Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->errors[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
        }
    }

    /**
     * Batch size
     */
    public function batchSize(): int
    {
        return 100;
    }

    /**
     * Chunk size
     */
    public function chunkSize(): int
    {
        return 100;
    }
}
