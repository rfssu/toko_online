<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oleh-Oleh Khas Jogja</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<script>
    const csrfToken = '{{ csrf_token() }}';
    async function addToCart(barangId, namaBarang) {
        @guest
            Swal.fire({
                icon: 'info',
                title: 'Login Required',
                text: 'Silahkan login terlebih dahulu untuk menambahkan produk ke keranjang',
                confirmButtonColor: '#d97706',
                confirmButtonText: 'Login Sekarang'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("login") }}';
                }
            });
            return;
        @endguest
    try {
            const response = await fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ barang_id: barangId, jumlah: 1 })
            });
            const data = await response.json();
            if (data.success) {
                // Update cart badge
                document.getElementById('cart-badge').textContent = data.cart_count;
                document.getElementById('cart-count-text').textContent = data.cart_count + ' Barang';
                document.getElementById('cart-total-text').textContent =
                    'Subtotal: Rp ' + new Intl.NumberFormat('id-ID').format(data.cart_total);

                // Show success alert
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message,
                    confirmButtonColor: '#d97706'
                });
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: 'Gagal menambahkan produk ke keranjang',
                confirmButtonColor: '#d97706'
            });
        }
    }
</script>

<body class="bg-gray-50 flex flex-col min-h-screen">

    {{-- PERHATIKAN PATH INCLUDE --}}
    @include('buyer.layouts.partials.header')

    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- PERHATIKAN PATH INCLUDE --}}
    @include('buyer.layouts.partials.footer')

</body>

</html>