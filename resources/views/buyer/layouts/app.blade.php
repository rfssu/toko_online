<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oleh-Oleh Khas Jogja</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
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