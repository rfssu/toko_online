<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="cupcake">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/css/custom.css'])
    @stack('styles')
</head>

<body class="font-sans antialiased bg-base-100">
    <div class="drawer lg:drawer-open">
        <input id="main-drawer" type="checkbox" class="drawer-toggle" />

        <!-- Konten Utama -->
        <div class="drawer-content flex flex-col min-h-screen">
            @include('seller/layouts/header')

            <!-- Konten Halaman dengan animasi fade -->
            <main class="flex-1 p-6 space-y-6 fade-in">
                <!-- Breadcrumbs yang lebih modern -->
                <div class="text-sm breadcrumbs">
                    <ul class="p-4 rounded-lg bg-base-200 shadow-sm">
                        <li><a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                                <i class="ri-home-2-fill"></i>
                                Home
                            </a></li>
                        <li>@yield('breadcrumb')</li>
                    </ul>
                </div>

                @yield('pages')
            </main>

            @include('seller/layouts/footer')
        </div>

        @include('seller/layouts/sidebar')
    </div>

    <!-- Scripts -->
    @vite(['resources/js/app.js', 'resources/js/main.js'])
    @stack('scripts')
    @include('seller/components/alert')
    @include('seller/components/modals')
</body>

</html>
