@extends('buyer.layouts.app')

@section('content')

    <!-- {{-- SECTION 1: HEADER --}}
        <div class="bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100">
            <div class="container mx-auto px-4 md:px-8 py-8 md:py-12">
                <div class="text-center">
                    <span class="bg-amber-600 text-white text-xs font-bold px-4 py-1.5 rounded-full mb-4 inline-block shadow-lg">
                        <i class="fa-solid fa-heart"></i> Tentang Kami
                    </span>
                    <h1 class="text-3xl md:text-4xl font-bold leading-tight mb-3 text-gray-900">
                        Cerita <span class="text-amber-600">Toko Kami</span>
                    </h1>
                    <p class="text-gray-600 md:text-lg max-w-2xl mx-auto">
                        Menghadirkan cita rasa otentik Yogyakarta dengan kualitas terbaik sejak 2015
                    </p>
                </div>
            </div>
        </div> -->

    <div class="container mx-auto px-4 md:px-8 py-4">
        <!-- pemberi jarak -->
    </div>

    {{-- SECTION 3: CERITA KAMI --}}
    <div class="container mx-auto px-4 md:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">

            {{-- Gambar --}}
            <div class="order-2 md:order-1">
                <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?q=80&w=800" alt="Toko Oleh-Oleh Jogja"
                    class="w-full h-64 md:h-80 object-cover rounded-2xl shadow-2xl rotate-2 hover:rotate-0 transition duration-500">
            </div>

            {{-- Text --}}
            <div class="order-1 md:order-2">
                <span class="bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1 rounded-full mb-4 inline-block">
                    <i class="fa-solid fa-calendar"></i> Sejak 2015
                </span>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4 leading-tight">
                    Membawa Rasa Asli Jogja ke Rumah Anda
                </h2>
                <div class="space-y-4 text-gray-600 leading-relaxed">
                    <p>
                        Berawal dari kecintaan mendalam terhadap kuliner tradisional Yogyakarta, kami hadir dengan misi
                        sederhana:
                        menghadirkan kehangatan dan kelezatan khas Jogja ke setiap rumah di Indonesia.
                    </p>
                    <p>
                        Setiap produk yang kami tawarkan bukan sekadar makanan, tetapi juga cerita tentang warisan resep
                        turun-temurun
                        yang dijaga dengan penuh cinta dan tanggung jawab.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 4: VISI & MISI --}}
    <div class="container mx-auto px-4 md:px-8 py-8">
        <h2 class="font-bold text-xl md:text-2xl mb-6 text-gray-800">Visi & Misi</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8">

            {{-- Visi --}}
            <div
                class="bg-white border border-gray-100 rounded-xl p-6 md:p-8 shadow-sm hover:shadow-xl transition duration-300">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-eye text-amber-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900">Visi Kami</h3>
                </div>
                <p class="text-gray-600 leading-relaxed">
                    Menjadi destinasi utama oleh-oleh khas Yogyakarta yang terpercaya,
                    menghadirkan produk berkualitas tinggi dengan cita rasa otentik ke seluruh Indonesia.
                </p>
            </div>

            {{-- Misi --}}
            <div
                class="bg-white border border-gray-100 rounded-xl p-6 md:p-8 shadow-sm hover:shadow-xl transition duration-300">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-bullseye text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900">Misi Kami</h3>
                </div>
                <p class="text-gray-600 leading-relaxed">
                    Melestarikan warisan kuliner Jogja dengan menyediakan produk berkualitas,
                    pelayanan terbaik, dan memberikan pengalaman berbelanja yang memuaskan.
                </p>
            </div>
        </div>
    </div>

    {{-- SECTION 5: NILAI-NILAI KAMI --}}
    <div class="container mx-auto px-4 md:px-8 py-8 pb-12">
        <h2 class="font-bold text-xl md:text-2xl mb-6 text-gray-800">Nilai-Nilai Kami</h2>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-8">

            {{-- Value 1 --}}
            <div
                class="group bg-white border border-gray-100 rounded-xl p-4 md:p-6 shadow-sm hover:shadow-xl transition duration-300 text-center">
                <div
                    class="w-14 h-14 md:w-16 md:h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition duration-300">
                    <i class="fa-solid fa-award text-amber-600 text-2xl md:text-3xl"></i>
                </div>
                <h3 class="text-sm md:text-base font-bold text-gray-900 mb-2">Kualitas Terjamin</h3>
                <p class="text-xs md:text-sm text-gray-500 leading-relaxed">
                    Produk bersertifikat BPOM dan Halal MUI
                </p>
            </div>

            {{-- Value 2 --}}
            <div
                class="group bg-white border border-gray-100 rounded-xl p-4 md:p-6 shadow-sm hover:shadow-xl transition duration-300 text-center">
                <div
                    class="w-14 h-14 md:w-16 md:h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition duration-300">
                    <i class="fa-solid fa-leaf text-green-600 text-2xl md:text-3xl"></i>
                </div>
                <h3 class="text-sm md:text-base font-bold text-gray-900 mb-2">100% Alami</h3>
                <p class="text-xs md:text-sm text-gray-500 leading-relaxed">
                    Tanpa bahan pengawet buatan
                </p>
            </div>

            {{-- Value 3 --}}
            <div
                class="group bg-white border border-gray-100 rounded-xl p-4 md:p-6 shadow-sm hover:shadow-xl transition duration-300 text-center">
                <div
                    class="w-14 h-14 md:w-16 md:h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition duration-300">
                    <i class="fa-solid fa-handshake text-blue-600 text-2xl md:text-3xl"></i>
                </div>
                <h3 class="text-sm md:text-base font-bold text-gray-900 mb-2">Kepercayaan</h3>
                <p class="text-xs md:text-sm text-gray-500 leading-relaxed">
                    Pelayanan terbaik untuk kepuasan Anda
                </p>
            </div>
        </div>
    </div>

@endsection