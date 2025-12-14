@extends('seller/layouts/main')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
        Dashboard
    </a>
@endsection
@section('pages')
    <div class="p-6">
        <!-- Header with Export -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">Dashboard Laba & Transaksi</h1>
                <p class="text-gray-600 mt-2">Periode: {{ ucfirst($period) }}</p>
            </div>

            <!-- Export Buttons -->
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-primary w-full sm:w-auto">
                    <i class="ri-download-line"></i>
                    Export Laporan
                    <i class="ri-arrow-down-s-line"></i>
                </label>
                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52 mt-2">
                    <li>
                        <a href="{{ route('dashboard.export', ['period' => $period, 'format' => 'xlsx']) }}" class="gap-2">
                            <i class="ri-file-excel-line text-green-600"></i>
                            Export Excel (.xlsx)
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard.export', ['period' => $period, 'format' => 'csv']) }}" class="gap-2">
                            <i class="ri-file-text-line text-blue-600"></i>
                            Export CSV (.csv)
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-2xl font-bold mb-4">
                📊 Grafik Pendapatan
                @if($period == 'today') - Hari Ini (Per Jam)
                @elseif($period == 'week') - Minggu Ini (Per Hari)
                @elseif($period == 'month') - Bulan Ini (Per Hari)
                @elseif($period == 'year') - Tahun Ini (Per Bulan)
                @else - 6 Bulan Terakhir
                @endif
            </h2>
            <canvas id="revenueChart" height="80"></canvas>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Revenue -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold mb-1 opacity-90">Total Pendapatan</h3>
                        <p class="text-3xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        <p class="text-xs opacity-75 mt-2">{{ $totalTransactions }} transaksi</p>
                    </div>
                    <div class="text-5xl opacity-20">
                        <i class="ri-money-dollar-circle-line"></i>
                    </div>
                </div>
            </div>

            <!-- Total Products Sold -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold mb-1 opacity-90">Total Produk Terjual</h3>
                        <p class="text-3xl font-bold">{{ number_format($totalProductsSold, 0, ',', '.') }}</p>
                        <p class="text-xs opacity-75 mt-2">Unit terjual</p>
                    </div>
                    <div class="text-5xl opacity-20">
                        <i class="ri-shopping-bag-3-line"></i>
                    </div>
                </div>
            </div>

            <!-- Unique Customers -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold mb-1 opacity-90">Pembeli Unik</h3>
                        <p class="text-3xl font-bold">{{ number_format($uniqueCustomers, 0, ',', '.') }}</p>
                        <p class="text-xs opacity-75 mt-2">Customer</p>
                    </div>
                    <div class="text-5xl opacity-20">
                        <i class="ri-team-line"></i>
                    </div>
                </div>
            </div>

            <!-- Growth -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold mb-1 opacity-90">Pertumbuhan</h3>
                        <p class="text-3xl font-bold">
                            @if($growthPercentage >= 0)
                                <i class="ri-arrow-up-line text-2xl"></i>
                            @else
                                <i class="ri-arrow-down-line text-2xl"></i>
                            @endif
                            {{ number_format(abs($growthPercentage), 1) }}%
                        </p>
                        <p class="text-xs opacity-75 mt-2">vs periode sebelumnya</p>
                    </div>
                    <div class="text-5xl opacity-20">
                        <i class="ri-line-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Periode -->
        <div class="bg-white p-4 rounded-lg shadow mb-6">
            <div class="flex gap-2">
                <a href="{{ route('dashboard', ['period' => 'today']) }}"
                    class="btn {{ $period == 'today' ? 'btn-primary' : 'btn-outline' }}">
                    Hari Ini
                </a>
                <a href="{{ route('dashboard', ['period' => 'week']) }}"
                    class="btn {{ $period == 'week' ? 'btn-primary' : 'btn-outline' }}">
                    Minggu Ini
                </a>
                <a href="{{ route('dashboard', ['period' => 'month']) }}"
                    class="btn {{ $period == 'month' ? 'btn-primary' : 'btn-outline' }}">
                    Bulan Ini
                </a>
                <a href="{{ route('dashboard', ['period' => 'year']) }}"
                    class="btn {{ $period == 'year' ? 'btn-primary' : 'btn-outline' }}">
                    Tahun Ini
                </a>
                <a href="{{ route('dashboard', ['period' => 'all']) }}"
                    class="btn {{ $period == 'all' ? 'btn-primary' : 'btn-outline' }}">
                    6 Bulan Terakhir
                </a>
            </div>
        </div>

        <!-- Tabel Transaksi -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-2xl font-bold mb-4">📋 Daftar Transaksi</h2>

            @if($transactions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr class="bg-gray-200">
                                <th>No</th>
                                <th>Kode Pesanan</th>
                                <th>Pembeli</th>
                                <th>Tanggal</th>
                                <th>Metode</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $index => $transaction)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="font-mono font-bold">#{{ $transaction->kode }}</td>
                                    <td>{{ $transaction->user->name ?? 'N/A' }}</td>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $transaction->payment_type ? ucfirst(str_replace('_', ' ', $transaction->payment_type)) : 'COD' }}
                                        </span>
                                    </td>
                                    <td class="text-right font-bold text-green-600">
                                        Rp {{ number_format($transaction->total, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-100 font-bold">
                                <td colspan="5" class="text-right">TOTAL:</td>
                                <td class="text-right text-green-600 text-xl">
                                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">📦</div>
                    <h3 class="text-xl font-bold text-gray-700">Belum Ada Transaksi</h3>
                    <p class="text-gray-500 mt-2">Transaksi yang sudah dibayar akan muncul di sini</p>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Chart Monthly -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-2xl font-bold mb-4">📈 Tren Periode</h2>

                @if($monthlyData->count() > 0)
                    <div class="space-y-4">
                        @php
                            $maxRevenue = $monthlyData->max('revenue');
                        @endphp
                        @foreach($monthlyData as $data)
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="font-semibold">
                                        @if($period == 'today')
                                            {{ $data->month }}
                                        @elseif($period == 'week' || $period == 'month')
                                            {{ \Carbon\Carbon::parse($data->month)->format('d M') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($data->month . '-01')->format('M Y') }}
                                        @endif
                                    </span>
                                    <span class="text-green-600 font-bold">Rp
                                        {{ number_format($data->revenue, 0, ',', '.') }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-6">
                                    <div class="bg-blue-500 h-6 rounded-full flex items-center px-2 text-white text-xs font-bold"
                                        style="width: {{ $maxRevenue > 0 ? ($data->revenue / $maxRevenue * 100) : 0 }}%">
                                        {{ $data->count }} transaksi
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">📊</div>
                        <p class="text-gray-500">Belum ada data</p>
                    </div>
                @endif
            </div>

            <!-- Top Products -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-2xl font-bold mb-4">🔥 Produk Terlaris</h2>

                @if($topProducts->count() > 0)
                    <div class="space-y-3">
                        @foreach($topProducts as $index => $product)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div
                                    class="text-2xl font-bold {{ $index === 0 ? 'text-yellow-500' : ($index === 1 ? 'text-gray-400' : 'text-orange-600') }}">
                                    #{{ $index + 1 }}
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold">{{ $product->nama_barang }}</p>
                                    <p class="text-sm text-gray-600">
                                        Terjual: {{ $product->total_sold ?? 0 }} unit
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-green-600">
                                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">🛍️</div>
                        <p class="text-gray-500">Belum ada produk terjual</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Revenue Chart
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const monthlyData = @json($monthlyData);
        const period = '{{ $period }}';

        const labels = monthlyData.map(item => {
            if (period === 'today') {
                // Show hour format
                return item.month;
            } else if (period === 'week' || period === 'month') {
                // Show day format
                const date = new Date(item.month);
                return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
            } else {
                // Show month format (year and all)
                const date = new Date(item.month + '-01');
                return date.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });
            }
        });

        const revenues = monthlyData.map(item => item.revenue);
        const counts = monthlyData.map(item => item.count);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: revenues,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: 'rgb(34, 197, 94)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                label += ' (' + counts[context.dataIndex] + ' transaksi)';
                                return label;
                            }
                        },
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID', {
                                    notation: 'compact',
                                    compactDisplay: 'short'
                                }).format(value);
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    </script>
@endsection