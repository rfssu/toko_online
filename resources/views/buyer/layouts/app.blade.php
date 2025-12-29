<!DOCTYPE html>
<html lang="id" data-theme="retro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oleh-Oleh Khas Jogja - Bakpia Yogyakarta</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    @vite(['resources/css/app.css', 'resources/css/custom.css'])

</head>
<script>
    const csrfToken = '{{ csrf_token() }}';
    async function addToCart(barangId, jumlah = 1) {
        @guest
            Swal.fire({
                icon: 'info',
                title: 'Login Required',
                text: 'Silahkan login terlebih dahulu untuk menambahkan produk ke keranjang',
                confirmButtonColor: '#d97706',
                confirmButtonText: 'Login Sekarang'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('login') }}';
                }
            });
            return;
        @endguest

    const form = $('<form>', {
            method: 'POST',
            action: '{{ route('cart.add') }}'
        });

        form.append($('<input>', {
            type: 'hidden',
            name: '_token',
            value: csrfToken
        }));

        form.append($('<input>', {
            type: 'hidden',
            name: 'barang_id',
            value: barangId
        }));
        form.append($('<input>', {
            type: 'hidden',
            name: 'jumlah',
            value: jumlah
        }));

        form.append($('<button>', {
            type: 'submit',
            style: 'display:none'
        }));

        $('body').append(form);
        form.submit();
    }
</script>

<body class="bg-gray-50 flex flex-col min-h-screen">

    @include('buyer.layouts.partials.header')

    <main class="flex-grow">
        @yield('content')
    </main>
    @vite(['resources/js/app.js', 'resources/js/main.js'])

    @include('buyer.layouts.partials.footer')
    @include('seller/components/alert')

</body>


</html>