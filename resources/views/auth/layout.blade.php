<!DOCTYPE html>
<html lang="id" data-theme="light"> <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Authentikasi - Toko Oleh-Oleh</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-base-200 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md p-6">
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-primary">Toko Oleh-Oleh</h1>
        </div>

        @yield('content')

        <div class="text-center mt-6 text-sm text-gray-500">
            &copy; {{ date('Y') }} Toko Oleh-Oleh
        </div>
    </div>

</body>
</html>