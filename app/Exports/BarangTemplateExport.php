<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class BarangTemplateExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
     * Return empty collection (template only)
     */
    public function collection()
    {
        // Return 3 sample rows as example
        return collect([
            [
                'nama_barang' => 'Bakpia Pathok Original',
                'harga' => 50000,
                'stok' => 100,
                'keterangan' => 'Rasa Coklat, Kemasan Box',
            ],
            [
                'nama_barang' => 'Gudeg Kaleng Bu Tjitro',
                'harga' => 35000,
                'stok' => 50,
                'keterangan' => 'Kemasan Kaleng 500gr',
            ],
            [
                'nama_barang' => 'Keripik Tempe Renyah',
                'harga' => 25000,
                'stok' => 200,
                'keterangan' => 'Rasa Original',
            ],
        ]);
    }

    /**
     * Column headers
     */
    public function headings(): array
    {
        return [
            'nama_barang',
            'harga',
            'stok',
            'keterangan',
        ];
    }

    /**
     * Apply styles
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4CAF50'],
                ],
            ],
        ];
    }
}
