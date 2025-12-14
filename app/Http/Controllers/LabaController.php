<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Barang;
use App\Exports\TransactionsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class LabaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // Get filter period (default: all time)
        $period = $request->get('period', 'all');

        // Get ALL paid orders (exclude only unpaid statuses)
        $query = Pesanan::whereNotIn('status', ['keranjang', 'pending_payment'])
            ->with(['pesanan_detail.barang', 'user']);

        // Apply period filter
        if ($period == 'today') {
            $query->whereDate('created_at', today());
        } elseif ($period == 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period == 'month') {
            $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        } elseif ($period == 'year') {
            $query->whereYear('created_at', now()->year);
        }

        // Get all transactions
        $transactions = $query->orderBy('created_at', 'desc')->get();

        // DEBUG: Check what we got
        \Log::info('Laba Dashboard Debug:', [
            'total_in_db' => Pesanan::count(),
            'status_counts' => Pesanan::select('status', \DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray(),
            'transactions_found' => $transactions->count(),
            'first_transaction' => $transactions->first() ? [
                'id' => $transactions->first()->id,
                'status' => $transactions->first()->status,
                'kode' => $transactions->first()->kode,
            ] : null
        ]);

        // Calculate metrics using accessor (total is computed attribute)
        $totalTransactions = $transactions->count();
        $totalRevenue = $transactions->sum(function ($transaction) {
            return $transaction->total; // Use accessor
        });

        // NEW METRICS
        // Calculate total products sold (unit count)
        $totalProductsSold = $transactions->sum(function ($transaction) {
            return $transaction->pesanan_detail->sum('jumlah');
        });

        // Calculate unique customers
        $uniqueCustomers = $transactions->pluck('user_id')->unique()->count();

        // Calculate growth (compare with previous period)
        $previousQuery = Pesanan::whereNotIn('status', ['keranjang', 'pending_payment'])
            ->with(['pesanan_detail.barang', 'user']);

        if ($period == 'today') {
            $previousQuery->whereDate('created_at', today()->subDay());
        } elseif ($period == 'week') {
            $previousQuery->whereBetween('created_at', [
                now()->subWeeks(2)->startOfWeek(),
                now()->subWeek()->endOfWeek()
            ]);
        } elseif ($period == 'month') {
            $previousQuery->whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year);
        } elseif ($period == 'year') {
            $previousQuery->whereYear('created_at', now()->subYear()->year);
        } else {
            // For 'all', compare with 6 months before that
            $previousQuery->whereBetween('created_at', [
                now()->subMonths(12),
                now()->subMonths(6)
            ]);
        }

        $previousTransactions = $previousQuery->get();
        $previousRevenue = $previousTransactions->sum(function ($transaction) {
            return $transaction->total;
        });

        // Calculate growth percentage
        if ($previousRevenue > 0) {
            $growthPercentage = (($totalRevenue - $previousRevenue) / $previousRevenue) * 100;
        } else {
            $growthPercentage = $totalRevenue > 0 ? 100 : 0;
        }

        // Calculate profit (if you have modal_price/cost in barang table)
        // For now, assume profit = revenue (you can modify this later)
        $totalProfit = $totalRevenue;

        // Get chart data based on period filter
        if ($period == 'today') {
            // Show hourly data for today
            $monthlyData = Pesanan::whereNotIn('status', ['keranjang', 'pending_payment'])
                ->whereDate('created_at', today())
                ->with('pesanan_detail')
                ->get()
                ->groupBy(function ($item) {
                    return $item->created_at->format('H:00');
                })
                ->map(function ($group, $hour) {
                    return (object) [
                        'month' => $hour,
                        'count' => $group->count(),
                        'revenue' => $group->sum(function ($item) {
                            return $item->total;
                        })
                    ];
                })
                ->sortKeys()
                ->values();
        } elseif ($period == 'week') {
            // Show daily data for this week
            $monthlyData = Pesanan::whereNotIn('status', ['keranjang', 'pending_payment'])
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->with('pesanan_detail')
                ->get()
                ->groupBy(function ($item) {
                    return $item->created_at->format('Y-m-d');
                })
                ->map(function ($group, $date) {
                    return (object) [
                        'month' => $date,
                        'count' => $group->count(),
                        'revenue' => $group->sum(function ($item) {
                            return $item->total;
                        })
                    ];
                })
                ->sortKeys()
                ->values();
        } elseif ($period == 'month') {
            // Show daily data for this month
            $monthlyData = Pesanan::whereNotIn('status', ['keranjang', 'pending_payment'])
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->with('pesanan_detail')
                ->get()
                ->groupBy(function ($item) {
                    return $item->created_at->format('Y-m-d');
                })
                ->map(function ($group, $date) {
                    return (object) [
                        'month' => $date,
                        'count' => $group->count(),
                        'revenue' => $group->sum(function ($item) {
                            return $item->total;
                        })
                    ];
                })
                ->sortKeys()
                ->values();
        } elseif ($period == 'year') {
            // Show monthly data for this year
            $monthlyData = Pesanan::whereNotIn('status', ['keranjang', 'pending_payment'])
                ->whereYear('created_at', now()->year)
                ->with('pesanan_detail')
                ->get()
                ->groupBy(function ($item) {
                    return $item->created_at->format('Y-m');
                })
                ->map(function ($group, $month) {
                    return (object) [
                        'month' => $month,
                        'count' => $group->count(),
                        'revenue' => $group->sum(function ($item) {
                            return $item->total;
                        })
                    ];
                })
                ->sortKeys()
                ->values();
        } else {
            // Show last 6 months for 'all'
            $monthlyData = Pesanan::whereNotIn('status', ['keranjang', 'pending_payment'])
                ->where('created_at', '>=', now()->subMonths(6))
                ->with('pesanan_detail')
                ->get()
                ->groupBy(function ($item) {
                    return $item->created_at->format('Y-m');
                })
                ->map(function ($group, $month) {
                    return (object) [
                        'month' => $month,
                        'count' => $group->count(),
                        'revenue' => $group->sum(function ($item) {
                            return $item->total;
                        })
                    ];
                })
                ->sortKeys()
                ->values();
        }

        // Get top selling products
        $topProducts = Barang::withCount([
            'pesanan_detail' => function ($q) {
                $q->whereHas('pesanan', function ($query) {
                    $query->whereNotIn('status', ['keranjang', 'pending_payment']);
                });
            }
        ])
            ->withSum([
                'pesanan_detail as total_sold' => function ($q) {
                    $q->whereHas('pesanan', function ($query) {
                        $query->whereNotIn('status', ['keranjang', 'pending_payment']);
                    });
                }
            ], 'jumlah')
            ->having('total_sold', '>', 0)
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        return view('seller.pages.dashboard', compact(
            'transactions',
            'totalTransactions',
            'totalRevenue',
            'totalProfit',
            'totalProductsSold',
            'uniqueCustomers',
            'growthPercentage',
            'monthlyData',
            'topProducts',
            'period'
        ));
    }

    public function export(Request $request)
    {
        $period = $request->get('period', 'all');
        $format = $request->get('format', 'xlsx'); // xlsx or csv

        // Get transactions with same filter as index
        $query = Pesanan::whereNotIn('status', ['keranjang', 'pending_payment'])
            ->with(['pesanan_detail.barang', 'user']);

        // Apply period filter
        if ($period == 'today') {
            $query->whereDate('created_at', today());
        } elseif ($period == 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period == 'month') {
            $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        } elseif ($period == 'year') {
            $query->whereYear('created_at', now()->year);
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();
        $totalRevenue = $transactions->sum(function ($transaction) {
            return $transaction->total;
        });

        // Generate filename
        $periodLabel = ucfirst($period);
        $filename = 'Laporan_Transaksi_' . $periodLabel . '_' . now()->format('Y-m-d_His') . '.' . $format;

        // Export
        return Excel::download(
            new TransactionsExport($transactions, $totalRevenue),
            $filename,
            $format === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX
        );
    }
}
