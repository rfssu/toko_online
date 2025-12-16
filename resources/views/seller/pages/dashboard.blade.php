@extends('seller/layouts/main')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
        Dashboard
    </a>
@endsection
@section('pages')
    <div class="p-6">
        <!-- Header with Export -->
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold">Dashboard</h1>
                <p class="text-gray-600 mt-2">Periode: {{ ucfirst($period) }}</p>
            </div>
            
            <!-- Export Buttons -->
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-primary gap-2">
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
        <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
            <h2 class="text-2xl font-bold mb-4">
                📊 Grafik Pendapatan 
                @if($period == 'today') - Hari Ini (Per Jam)
                @elseif($period == 'week') - Minggu Ini (Per Hari)
                @elseif($period == 'month') - Bulan Ini (Per Hari)
                @elseif($period == 'year') - Tahun Ini (Per Bulan)
                @else - 6 Bulan Terakhir
                @endif
            </h2>
            <canvas id="revenueChart" height="70"></canvas>
        </div>

        <!-- Stats Cards (2 only) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Total Revenue -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-8 rounded-lg shadow-lg hover:shadow-xl transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold mb-2 opacity-90">Total Pendapatan</h3>
                        <p class="text-4xl font-bold mb-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        <p class="text-sm opacity-75 mt-3">{{ $totalTransactions }} transaksi</p>
                    </div>
                    <div class="text-7xl opacity-20">
                        <i class="ri-money-dollar-circle-line"></i>
                    </div>
                </div>
            </div>
            
            <!-- Total Products Sold -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-8 rounded-lg shadow-lg hover:shadow-xl transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold mb-2 opacity-90">Total Produk Terjual</h3>
                        <p class="text-4xl font-bold mb-1">{{ number_format($totalProductsSold, 0, ',', '.') }}</p>
                        <p class="text-sm opacity-75 mt-3">Unit terjual</p>
                    </div>
                    <div class="text-7xl opacity-20">
                        <i class="ri-shopping-bag-3-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Periode -->
        <div class="bg-white p-4 rounded-lg shadow mb-6">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('dashboard', ['period' => 'today']) }}" 
                   class="btn btn-sm {{ $period == 'today' ? 'btn-primary' : 'btn-outline' }}">
                    Hari Ini
                </a>
                <a href="{{ route('dashboard', ['period' => 'week']) }}" 
                   class="btn btn-sm {{ $period == 'week' ? 'btn-primary' : 'btn-outline' }}">
                    Minggu Ini
                </a>
                <a href="{{ route('dashboard', ['period' => 'month']) }}" 
                   class="btn btn-sm {{ $period == 'month' ? 'btn-primary' : 'btn-outline' }}">
                    Bulan Ini
                </a>
                <a href="{{ route('dashboard', ['period' => 'year']) }}" 
                   class="btn btn-sm {{ $period == 'year' ? 'btn-primary' : 'btn-outline' }}">
                    Tahun Ini
                </a>
                <a href="{{ route('dashboard', ['period' => 'all']) }}" 
                   class="btn btn-sm {{ $period == 'all' ? 'btn-primary' : 'btn-outline' }}">
                    6 Bulan Terakhir
                </a>
            </div>
        </div>

        <!-- Top Products Only -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-4">� Produk Terlaris</h2>
            
            @if($topProducts->count() > 0)
                <div class="space-y-3">
                    @foreach($topProducts as $index => $product)
                        <div class="flex items-center gap-4 p-4 bg-base-200 rounded-lg hover:bg-base-300 transition">
                            <div class="text-3xl font-bold {{ $index === 0 ? 'text-yellow-500' : ($index === 1 ? 'text-gray-400' : ($index === 2 ? 'text-orange-600' : 'text-gray-600')) }}">
                                #{{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-lg">{{ $product->nama_barang }}</p>
                                <p class="text-sm text-gray-600">
                                    Terjual: <span class="font-semibold text-primary">{{ $product->total_sold ?? 0 }} unit</span>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-green-600 text-lg">
                                    Rp {{ number_format($product->harga, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-500">per unit</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <div class="text-6xl mb-4 opacity-30">🛍️</div>
                    <p class="text-gray-500">Belum ada produk terjual</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const monthlyData = @json($monthlyData);
        const period = '{{ $period }}';
        
        const labels = monthlyData.map(item => {
            if (period === 'today') {
                return item.month;
            } else if (period === 'week' || period === 'month') {
                const date = new Date(item.month);
                return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
            } else {
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
                            label: function(context) {
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
                            callback: function(value) {
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