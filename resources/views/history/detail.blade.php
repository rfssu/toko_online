@extends('buyer.layouts.app')
@php
    use App\Models\Pesanan;
@endphp

@section('content')
    {{-- Header Section --}}
    <div class="bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100">
        <div class="container mx-auto px-4 md:px-8 py-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('history') }}" class="btn btn-circle btn-ghost">
                    <i class="fa-solid fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                        Detail Pesanan
                    </h1>
                    <p class="text-gray-600 mt-1">Order #{{ $pesanan->kode }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 md:px-8 py-8">
        <div class="max-w-5xl mx-auto">
            {{-- Order Status Card --}}
            <div class="card bg-base-100 shadow-xl mb-6">
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Status --}}
                        <div class="text-center md:text-left">
                            <p class="text-sm text-gray-600 mb-2">Status Pesanan</p>
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

                        {{-- Date --}}
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-2">Tanggal Pesanan</p>
                            <p class="font-bold text-gray-900">
                                <i class="fa-solid fa-calendar text-amber-600 mr-1"></i>
                                {{ $pesanan->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>

                        {{-- Payment Method --}}
                        <div class="text-center md:text-right">
                            <p class="text-sm text-gray-600 mb-2">Metode Pembayaran</p>
                            <p class="font-bold text-gray-900">
                                <i class="fa-solid fa-credit-card text-amber-600 mr-1"></i>
                                {{ $pesanan->payment_type ? ucfirst(str_replace('_', ' ', $pesanan->payment_type)) : 'Midtrans' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Items Card --}}
            <div class="card bg-base-100 shadow-xl mb-6">
                <div class="card-body">
                    <h2 class="card-title text-2xl mb-4">
                        <i class="fa-solid fa-shopping-bag text-amber-600"></i>
                        Item Pesanan
                    </h2>

                    @if($pesanan_details->count() > 0)
                        <div class="space-y-4">
                            @foreach($pesanan_details as $detail)
                                <div class="flex gap-4 p-4 bg-base-200 rounded-lg">
                                    {{-- Product Image --}}
                                    <div class="avatar">
                                        <div class="w-20 h-20 rounded-lg">
                                            @if($detail->barang->file('gambar')->hasFile())
                                                <img src="{{ $detail->barang->file('gambar')->preview() }}"
                                                    alt="{{ $detail->barang->nama_barang }}" class="object-cover">
                                            @else
                                                <div class="bg-gray-300 w-full h-full flex items-center justify-center">
                                                    <img src="{{ Vite::asset('resources/assets/photos/bakpia.jpg') }}"
                                                        alt="{{ $detail->barang->nama_barang }}" class="object-cover">
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Product Info --}}
                                    <div class="flex-1">
                                        <h3 class="font-bold text-lg">{{ $detail->barang->nama_barang }}</h3>
                                        <p class="text-sm text-gray-600">
                                            {{ $detail->jumlah }} x Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    {{-- Price --}}
                                    <div class="text-right">
                                        <p class="font-bold text-amber-600 text-lg">
                                            Rp {{ number_format($detail->total, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Total Summary --}}
                        <div class="divider"></div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-lg">
                                <span class="text-gray-600">Subtotal ({{ $pesanan_details->count() }} item)</span>
                                <span class="font-semibold">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-lg">
                                <span class="text-gray-600">Biaya Layanan</span>
                                <span class="font-semibold text-green-600">GRATIS</span>
                            </div>
                            <div class="divider my-2"></div>
                            <div class="flex justify-between text-2xl font-bold">
                                <span>Total</span>
                                <span class="text-amber-600">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <i class="fa-solid fa-box-open text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Tidak ada item dalam pesanan ini</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- QR Code Card (for paid orders) --}}
            @if($pesanan->status === App\Models\Pesanan::STATUS_CO || $pesanan->status === App\Models\Pesanan::STATUS_PICKUP)
                <div class="card bg-gradient-to-br from-amber-50 to-orange-50 shadow-xl mb-6">
                    <div class="card-body text-center">
                        <h3 class="card-title justify-center text-2xl mb-2">
                            <i class="fa-solid fa-qrcode text-amber-600"></i>
                            QR Code Pickup
                        </h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Tunjukkan QR code ini kepada kasir saat mengambil pesanan
                        </p>

                        {{-- QR Code --}}
                        <div class="flex justify-center mb-4 bg-white p-4 rounded-lg inline-block mx-auto">
                            {!! $pesanan->qr_code !!}
                        </div>

                        <div class="alert alert-info">
                            <i class="fa-solid fa-info-circle"></i>
                            <span class="text-sm">
                                <strong>Order #{{ $pesanan->kode }}</strong><br>
                                Tunjukkan QR code ini atau sebutkan nomor pesanan
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Action Buttons (Payment Only) --}}
            @if($pesanan->status == 'pending_payment' && $pesanan->snap_token)
                <div class="flex justify-center mt-6">
                    <button onclick="retryPayment('{{ $pesanan->snap_token }}')" class="btn btn-warning btn-lg">
                        <i class="fa-solid fa-credit-card"></i>
                        Lanjutkan Pembayaran
                    </button>
                </div>
            @endif
        </div>
    </div>
    </div>

    {{-- Midtrans Snap Script --}}
    @if(config('midtrans.is_production'))
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @else
        <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif

    <script>
        function retryPayment(snapToken) {
            snap.pay(snapToken, {
                onSuccess: function (result) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil!',
                        text: 'Pesanan Anda sedang diproses',
                    }).then(() => {
                        window.location.reload();
                    });
                },
                onPending: function (result) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Pembayaran Pending',
                        text: 'Menunggu konfirmasi pembayaran',
                    });
                },
                onError: function (result) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Pembayaran Gagal',
                        text: 'Terjadi kesalahan, silahkan coba lagi',
                    });
                }
            });
        }
    </script>
@endsection