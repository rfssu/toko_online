@extends('seller/layouts/main')

@section('pages')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            {{-- Header --}}
            <div class="mb-6">
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('pesanans.index') }}" class="btn btn-ghost btn-sm">
                        <i class="ri-arrow-left-line"></i>
                    </a>
                    <h1 class="text-3xl font-bold">
                        <i class="ri-qr-scan-2-line text-primary"></i>
                        QR Code Scanner
                    </h1>
                </div>
                <p class="text-gray-600 ml-12">Scan QR code pesanan untuk konfirmasi pickup</p>
            </div>

            {{-- Scanner Container --}}
            <div class="card bg-base-100 shadow-xl mb-6">
                <div class="card-body">
                    {{-- Camera Preview --}}
                    <div id="reader" class="mb-4" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>

                    {{-- Scan Result --}}
                    <div id="scan-result" class="hidden">
                        <div class="alert alert-success mb-4">
                            <i class="ri-qr-code-line"></i>
                            <span>QR Code berhasil di-scan!</span>
                        </div>

                        {{-- Order Details --}}
                        <div id="order-details" class="bg-base-200 p-6 rounded-lg">
                            <h3 class="text-xl font-bold mb-4">Detail Pesanan</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-sm text-gray-600">Nomor Pesanan</p>
                                    <p class="font-bold" id="order-code"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Nama Pembeli</p>
                                    <p class="font-bold" id="buyer-name"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Total Pembayaran</p>
                                    <p class="font-bold text-primary" id="order-total"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Status</p>
                                    <p class="font-bold" id="order-status"></p>
                                </div>
                            </div>

                            {{-- Items List --}}
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">Item Pesanan</p>
                                <div class="overflow-x-auto">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Produk</th>
                                                <th class="text-center">Qty</th>
                                                <th class="text-right">Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody id="items-list"></tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex gap-3 justify-end">
                                <button onclick="resetScanner()" class="btn btn-ghost">
                                    <i class="ri-arrow-left-line"></i>
                                    Scan Lagi
                                </button>
                                <button onclick="confirmPickup()" id="confirm-btn" class="btn btn-primary">
                                    <i class="ri-check-line"></i>
                                    Konfirmasi Pickup
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Error Message --}}
                    <div id="scan-error" class="alert alert-error hidden">
                        <i class="ri-error-warning-line"></i>
                        <span id="error-message"></span>
                    </div>
                </div>
            </div>

            {{-- Info Card --}}
            <div class="alert alert-info">
                <i class="ri-information-line"></i>
                <div>
                    <h4 class="font-bold">Cara Menggunakan Scanner:</h4>
                    <ul class="text-sm mt-2 list-disc list-inside">
                        <li>Izinkan akses kamera saat diminta</li>
                        <li>Arahkan QR code ke kamera</li>
                        <li>Tunggu hingga QR code ter-scan otomatis</li>
                        <li>Periksa detail pesanan dan klik Konfirmasi Pickup</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- HTML5 QR Code Scanner Library --}}
    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
        let currentPesananId = null;
        let html5QrCode = null;

        // Initialize scanner on page load
        document.addEventListener('DOMContentLoaded', function() {
            initScanner();
        });

        function initScanner() {
            html5QrCode = new Html5Qrcode("reader");

            const config = {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                },
                aspectRatio: 1.0
            };

            html5QrCode.start({
                    facingMode: "environment"
                },
                config,
                onScanSuccess,
                onScanError
            ).catch(err => {
                console.error('Failed to start scanner:', err);
                showError('Gagal mengakses kamera. Pastikan izin kamera disetujui.');
            });
        }

        function onScanSuccess(decodedText, decodedResult) {
            // Stop scanner
            html5QrCode.stop();

            // Verify QR code untuk dapat kode pesanan
            fetch('{{ route('pesanans.verify-qr') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        qr_data: decodedText
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirect dengan kode pesanan yang sudah di-decrypt
                        window.location.href = '{{ route('pesanans.index') }}?per_page=10&status=&search=' + data.pesanan.kode;
                    } else {
                        showError(data.message || 'QR Code tidak valid');
                        setTimeout(resetScanner, 3000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('Terjadi kesalahan saat memverifikasi QR code');
                    setTimeout(resetScanner, 3000);
                });
        }

        function onScanError(error) {
            // Silent error handling
        }

        function displayOrderDetails(pesanan) {
            currentPesananId = pesanan.id;

            // Fill order details
            document.getElementById('order-code').textContent = '#' + pesanan.kode;
            document.getElementById('buyer-name').textContent = pesanan.user.name;
            document.getElementById('order-total').textContent = 'Rp ' + formatNumber(pesanan.total);
            document.getElementById('order-status').textContent = pesanan.status_val;

            // Fill items list
            const itemsList = document.getElementById('items-list');
            itemsList.innerHTML = '';
            pesanan.pesanan_detail.forEach(detail => {
                const row = `
                        <tr>
                            <td>${detail.barang.nama_barang}</td>
                            <td class="text-center">${detail.jumlah}</td>
                            <td class="text-right">Rp ${formatNumber(detail.harga)}</td>
                        </tr>
                    `;
                itemsList.innerHTML += row;
            });

            // Show result
            document.getElementById('scan-result').classList.remove('hidden');
            document.getElementById('scan-error').classList.add('hidden');
        }

        function confirmPickup() {
            if (!currentPesananId) return;

            const btn = document.getElementById('confirm-btn');
            btn.disabled = true;
            btn.innerHTML = '<span class="loading loading-spinner"></span> Processing...';

            fetch('{{ route('pesanans.confirm-pickup-qr') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        pesanan_id: currentPesananId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Pickup berhasil dikonfirmasi',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            resetScanner();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message || 'Terjadi kesalahan'
                        });
                        btn.disabled = false;
                        btn.innerHTML = '<i class="ri-check-line"></i> Konfirmasi Pickup';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat konfirmasi pickup'
                    });
                    btn.disabled = false;
                    btn.innerHTML = '<i class="ri-check-line"></i> Konfirmasi Pickup';
                });
        }

        function resetScanner() {
            currentPesananId = null;
            document.getElementById('scan-result').classList.add('hidden');
            document.getElementById('scan-error').classList.add('hidden');

            // Restart scanner
            initScanner();
        }

        function showError(message) {
            document.getElementById('error-message').textContent = message;
            document.getElementById('scan-error').classList.remove('hidden');
            document.getElementById('scan-result').classList.add('hidden');
        }

        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }
    </script>
@endsection
