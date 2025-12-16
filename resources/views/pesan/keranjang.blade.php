@extends('buyer.layouts.app')

@section('content')
    {{-- Header Section --}}
    <div class="bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100">
        <div class="container mx-auto px-4 md:px-8 py-8">
            <div class="text-center">
                <span class="bg-amber-600 text-white text-xs font-bold px-4 py-1.5 rounded-full mb-4 inline-block shadow-lg">
                    <i class="fa-solid fa-shopping-cart"></i> Keranjang Belanja
                </span>
                <h1 class="text-3xl md:text-4xl font-bold leading-tight mb-3 text-gray-900">
                    Review <span class="text-amber-600">Pesanan Anda</span>
                </h1>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 md:px-8 py-8">
        @if (!$pesanan_details->isEmpty())
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Cart Items --}}
                <div class="lg:col-span-2">
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body">
                            <h2 class="card-title text-xl mb-4">
                                <i class="fa-solid fa-cart-shopping text-amber-600"></i>
                                Item dalam Keranjang ({{ $pesanan_details->count() }})
                            </h2>

                            <div class="space-y-4">
                                @foreach ($pesanan_details as $detail)
                                    <div class="flex gap-4 p-4 bg-base-200 rounded-lg hover:shadow-md transition">
                                        {{-- Product Image --}}
                                        <div class="avatar">
                                            <div class="w-20 h-20 rounded-lg">
                                                @if ($detail->barang->file('gambar')->hasFile())
                                                    <img src="{{ $detail->barang->file('gambar')->preview() }}" alt="{{ $detail->barang->nama_barang }}" class="object-cover">
                                                @else
                                                    <div class="bg-gray-300 w-full h-full flex items-center justify-center">
                                                        <img src="{{ Vite::asset('resources/assets/photos/bakpia.jpg') }}" alt="{{ $detail->barang->nama_barang }}" class="object-cover">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Product Info --}}
                                        <div class="flex-1">
                                            <h3 class="font-bold text-gray-800">{{ $detail->barang->nama_barang }}</h3>
                                            <p class="text-sm text-gray-600">{{ $detail->jumlah }} pcs × Rp
                                                {{ number_format($detail->barang->harga, 0, ',', '.') }}
                                            </p>
                                            <p class="text-amber-600 font-bold mt-1 mb-5">
                                                Rp {{ number_format($detail->total_keranjang, 0, ',', '.') }}
                                            </p>
                                            <div class="flex items-center">
                                                <button onclick="addToCart({{ $detail->barang_id }}, -1)" class="btn btn-outline btn-accent btn-xs">
                                                    <i class="fa-solid fa-minus"></i>
                                                </button>
                                                <span class="text-sm font-semibold w-8 text-center">{{ $detail->jumlah }}</span>
                                                <button onclick="addToCart({{ $detail->barang_id }})" class="btn btn-outline btn-accent btn-xs">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Delete Button --}}
                                        <div class="flex items-center">
                                            <a href="{{ route('pesanan.delete', $detail->id) }}" onclick="event.preventDefault(); confirmDelete(this.href);" class="btn btn-ghost btn-sm btn-circle text-error hover:bg-error hover:text-white">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="lg:col-span-1">
                    <div class="card bg-base-100 shadow-xl sticky top-24">
                        <div class="card-body">
                            <h2 class="card-title text-xl mb-4">Ringkasan Pesanan</h2>

                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal ({{ $pesanan_details->count() }} item)</span>
                                    <span class="font-semibold">Rp
                                        {{ number_format($user->barang_keranjang->sum('total_keranjang') ?? 0, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Biaya Layanan</span>
                                    <span class="font-semibold text-green-600">GRATIS</span>
                                </div>
                                <div class="divider my-2"></div>
                                <div class="flex justify-between text-lg font-bold">
                                    <span>Total</span>
                                    <span class="text-amber-600">Rp
                                        {{ number_format($user->barang_keranjang->sum('total_keranjang') ?? 0, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="bg-amber-50 p-3 rounded-lg mb-4">
                                <p class="text-xs text-gray-700">
                                    <i class="fa-solid fa-info-circle text-amber-600"></i>
                                    Pesanan akan disiapkan setelah konfirmasi. Ambil di toko setelah status "Siap Pickup".
                                </p>
                            </div>

                            {{-- Bayar Sekarang Button --}}
                            <button type="button" onclick="confirmCheckout()" class="btn bg-amber-600 hover:bg-amber-700 text-white w-full border-none">
                                <i class="fa-solid fa-credit-card"></i>
                                Bayar Sekarang
                            </button>

                            <a href="{{ route('produk') }}" class="btn btn-ghost w-full mt-2">
                                <i class="fa-solid fa-arrow-left"></i>
                                Lanjut Belanja
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Empty Cart State --}}
            <div class="card bg-base-100 shadow-xl max-w-2xl mx-auto">
                <div class="card-body text-center py-16">
                    <i class="fa-solid fa-cart-shopping text-6xl text-gray-300 mb-4"></i>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Keranjang Kosong</h2>
                    <p class="text-gray-600 mb-6">Belum ada produk dalam keranjang Anda</p>
                    <a href="{{ route('produk') }}" class="btn bg-amber-600 hover:bg-amber-700 text-white border-none w-full max-w-xs mx-auto">
                        <i class="fa-solid fa-shopping-bag"></i>
                        Mulai Belanja
                    </a>
                </div>
            </div>
        @endif
    </div>

    {{-- Midtrans Snap Script --}}
    @if (config('midtrans.is_production'))
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @else
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif

    <script>
        // SweetAlert for Delete Confirmation
        function confirmDelete(url) {
            Swal.fire({
                title: 'Hapus Item?',
                text: 'Item ini akan dihapus dari keranjang',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d97706',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }

        // Checkout with Midtrans Snap
        function confirmCheckout() {
            Swal.fire({
                title: 'Konfirmasi Pesanan',
                text: 'Anda akan melanjutkan ke pembayaran',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d97706',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Membuat token pembayaran',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // AJAX to konfirmasi and get snap token
                    fetch('{{ route('pesanan.konfirmasi') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Konfirmasi Response:', data);

                            if (data.success && data.snap_token) {
                                Swal.close();

                                // Trigger Snap popup
                                snap.pay(data.snap_token, {
                                    onSuccess: function(result) {
                                        console.log('Payment Success:', result);

                                        fetch('/payment/update-status', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                },
                                                body: JSON.stringify({
                                                    order_id: result.order_id,
                                                    transaction_status: 'settlement',
                                                    payment_type: result.payment_type
                                                })
                                            }).then(response => response.json())
                                            .then(() => {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Pembayaran Berhasil!',
                                                    confirmButtonText: 'Lihat Pesanan'
                                                }).then(() => {
                                                    window.location.href = '{{ route('history') }}';
                                                });
                                            });
                                    },
                                    onPending: function(result) {
                                        console.log('Payment Pending:', result);
                                        Swal.fire({
                                            icon: 'info',
                                            title: 'Pembayaran Pending',
                                            text: 'Menunggu konfirmasi pembayaran',
                                        }).then(() => {
                                            window.location.href = '{{ route('history') }}';
                                        });
                                    },
                                    onError: function(result) {
                                        console.log('Payment Error:', result);
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Pembayaran Gagal',
                                            text: 'Terjadi kesalahan, silahkan coba lagi',
                                        });
                                    },
                                    onClose: function() {
                                        console.log('Payment Popup Closed');
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: data.message || 'Gagal membuat token pembayaran',
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan sistem',
                            });
                        });
                }
            });
        }
    </script>
@endsection
