<?php

namespace App\Exports;

use App\Models\Pesanan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $transactions;
    protected $totalRevenue;

    public function __construct($transactions, $totalRevenue)
    {
        $this->transactions = $transactions;
        $this->totalRevenue = $totalRevenue;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->transactions;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Kode Pesanan',
            'Pembeli',
            'Email',
            'Tanggal',
            'Metode Pembayaran',
            'Total (Rp)',
        ];
    }

    /**
     * @param mixed $transaction
     */
    public function map($transaction): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $transaction->kode,
            $transaction->user->name ?? 'N/A',
            $transaction->user->email ?? 'N/A',
            $transaction->created_at->format('d/m/Y H:i'),
            $transaction->payment_type ? ucfirst(str_replace('_', ' ', $transaction->payment_type)) : 'COD',
            $transaction->total,
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
