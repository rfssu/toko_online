@extends('buyer.layouts.app')
@php
    use App\Models\Pesanan;
@endphp

@section('content')
    <div class="container mx-auto px-4 md:px-8 py-8">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">
                <i class="fa-solid fa-clock-rotate-left text-amber-600"></i>
                Riwayat Pesanan
            </h1>

            @if ($pesanans->count() > 0)
                <div class="space-y-4">
                    @foreach ($pesanans as $pesanan)
                        <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition">
                            <div class="card-body">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    {{-- Order Info --}}
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="font-bold text-lg">Order #{{ $pesanan->kode }}</h3>
                                            <span class="badge badge-lg text-white 
                                                            {{ $pesanan->status == Pesanan::STATUS_CO ? 'badge-info' : '' }}
                                                            {{ $pesanan->status == Pesanan::STATUS_PICKUP ? 'badge-success' : '' }}
                                                            {{ $pesanan->status == Pesanan::STATUS_PENDING ? 'badge-warning' : '' }}">
                                                <i class="fa-solid 
                                                                {{ $pesanan->status == Pesanan::STATUS_CO ? 'fa-box' : '' }}
                                                                {{ $pesanan->status == Pesanan::STATUS_PICKUP ? 'fa-check' : '' }}
                                                                {{ $pesanan->status == Pesanan::STATUS_PENDING ? 'fa-clock' : '' }}
                                                                mr-1"></i>
                                                {{ $pesanan->status_val }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            <i class="fa-solid fa-calendar mr-1"></i>
                                            {{ $pesanan->created_at->format('d M Y, H:i') }}
                                        </p>
                                        @if ($pesanan->payment_type)
                                            <p class="text-sm text-gray-600 mt-1">
                                                <i class="fa-solid fa-credit-card mr-1"></i>
                                                {{ ucfirst(str_replace('_', ' ', $pesanan->payment_type)) }}
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Items Count & Total --}}
                                    <div class="text-center md:text-right">
                                        <p class="text-sm text-gray-600 mb-1">
                                            {{ $pesanan->pesanan_detail->count() }} Item
                                        </p>
                                        <p class="text-2xl font-bold text-amber-600">
                                            Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Items Preview --}}
                                <div class="divider my-2"></div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($pesanan->pesanan_detail->take(3) as $detail)
                                        <div class="badge badge-lg badge-ghost">
                                            {{ $detail->barang->nama_barang }} ({{ $detail->jumlah }}x)
                                        </div>
                                    @endforeach
                                    @if ($pesanan->pesanan_detail->count() > 3)
                                        <div class="badge badge-lg badge-ghost">
                                            +{{ $pesanan->pesanan_detail->count() - 3 }} lainnya
                                        </div>
                                    @endif
                                </div>

                                {{-- Action Buttons --}}
                                <div class="card-actions justify-end mt-4">
                                    @if ($pesanan->status == 'pending_payment' && $pesanan->snap_token)
                                        <button onclick="retryPayment('{{ $pesanan->snap_token }}')" class="btn btn-warning btn-sm">
                                            <i class="fa-solid fa-credit-card"></i>
                                            Bayar Sekarang
                                        </button>
                                    @endif
                                    <a href="{{ route('history.detail', $pesanan->id) }}" class="btn bg-amber-600 hover:bg-amber-700 text-white border-none btn-sm">
                                        <i class="fa-solid fa-eye"></i>
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body text-center py-16">
                        <i class="fa-solid fa-box-open text-6xl text-gray-300 mb-4"></i>
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Belum Ada Pesanan</h2>
                        <p class="text-gray-600 mb-6">Anda belum memiliki riwayat pesanan</p>
                        <a href="{{ route('produk') }}" class="btn bg-amber-600 hover:bg-amber-700 text-white border-none w-full max-w-xs mx-auto">
                            <i class="fa-solid fa-shopping-bag"></i>
                            Mulai Belanja
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Midtrans Snap Script (for retry payment) --}}
    @if (config('midtrans.is_production'))
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @else
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif

    <script>
        function retryPayment(snapToken) {
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil!',
                        text: 'Pesanan Anda sedang diproses',
                    }).then(() => {
                        window.location.reload();
                    });
                },
                onPending: function(result) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Pembayaran Pending',
                        text: 'Menunggu konfirmasi pembayaran',
                    }).then(() => {
                        window.location.reload();
                    });
                },
                onError: function(result) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Pembayaran Gagal',
                        text: 'Terjadi kesalahan, silahkan coba lagi',
                    });
                },
                onClose: function() {
                    console.log('Payment popup closed');
                }
            });
        }
    </script>
@endsection
