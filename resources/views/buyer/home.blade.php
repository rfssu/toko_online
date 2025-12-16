@extends('buyer.layouts.app')

@section('content')
    {{-- CSS Tambahan untuk Hide Scrollbar (Kategori) --}}
    <style>
        .hide-scroll::-webkit-scrollbar {
            display: none;
        }

        .hide-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    {{-- SECTION 1: HERO BANNER (Persis Prototype) --}}
    <div class="bg-gray-100">
        <div class="container mx-auto px-4 md:px-8 py-8 md:py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                {{-- Text Area --}}
                <div class="order-2 md:order-1 text-center md:text-left">
                    <span class="bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1 rounded-full mb-4 inline-block">
                        <i class="fa-solid fa-award"></i> Oleh-oleh Terlengkap #1
                    </span>
                    <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4 text-gray-900">
                        Bawa Pulang Rasa <br><span class="text-amber-600">Asli Jogja</span> Hari Ini.
                    </h1>
                    <p class="text-gray-600 mb-6 md:text-lg">
                        Tanpa bahan pengawet, dikemas vakum aman perjalanan jauh, dan garansi rasa otentik.
                    </p>
                    <div class="flex gap-3 justify-center md:justify-start">
                        <button class="btn bg-amber-600 hover:bg-amber-700 text-white border-none rounded-full px-8 shadow-lg shadow-amber-200">
                            Pesan Sekarang
                        </button>
                        <button class="btn bg-white text-gray-700 hover:bg-gray-50 border-gray-300 rounded-full px-6">
                            Lihat Menu
                        </button>
                    </div>
                </div>
                {{-- Image Area --}}
                <div class="order-1 md:order-2">
                    <img src="https://images.unsplash.com/photo-1558961363-fa8fdf82db35?q=80&w=1000" alt="Hero Image" class="w-full h-64 md:h-96 object-cover rounded-2xl shadow-2xl rotate-2 hover:rotate-0 transition duration-500">
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 2: BEST SELLER --}}
    <div class="container mx-auto px-4 md:px-8 py-8">
        <div class="flex justify-between items-end mb-6">
            <h2 class="font-bold text-xl md:text-2xl text-gray-800">Best Seller 🔥</h2>
            <a href="{{ route('produk') }}" class="text-amber-600 text-sm font-semibold hidden md:block hover:underline">Lihat Semua Produk</a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">

            {{-- LOOPING BEST SELLER --}}
            @forelse($best_sellers as $item)
                <div class="group bg-white border border-gray-100 rounded-xl p-3 md:p-4 shadow-sm hover:shadow-xl transition duration-300">

                    {{-- Gambar Produk --}}
                    <div class="relative aspect-square bg-gray-100 rounded-lg mb-3 overflow-hidden">
                        <img src="{{ $item->file('gambar')->hasFile() ? $item->file('gambar')->preview() : Vite::asset('resources/assets/photos/bakpia.jpg') }}" alt="{{ $item->nama_barang }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">

                        {{-- Badge Best Seller --}}
                        @if ($item->stok_ready < 1)
                            <span class="absolute top-2 right-2 bg-gray-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow">HABIS</span>
                        @elseif($item->stok_ready < 5)
                            <span class="absolute top-2 right-2 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow">HAMPIR HABIS</span>
                        @else
                            <span class="absolute top-2 right-2 bg-amber-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow">BEST</span>
                        @endif
                    </div>

                    {{-- Badge Info --}}
                    <div class="mb-1">
                        <span class="text-[10px] md:text-xs text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded border border-green-100">
                            {{ $item->keterangan ?? 'Tahan 7 Hari' }}
                        </span>
                    </div>


                    {{-- Nama Produk --}}
                    <h3 class="font-bold text-gray-800 text-sm md:text-base mb-1 line-clamp-1" title="{{ $item->nama_barang }}">
                        {{ $item->nama_barang }}
                    </h3>

                    {{-- Harga & Button --}}
                    <div class="flex justify-between items-end mt-2">
                        <div>
                            <p class="text-amber-600 font-bold text-base md:text-lg">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- Button Add --}}
                        @if ($item->stok_ready > 0)
                            <button onclick="addToCart({{ $item->id }})" class="bg-gray-900 text-white w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center hover:bg-amber-600 transition shadow-lg transform active:scale-90">
                                <i class="fa-solid fa-cart-plus"></i>
                            </button>
                        @else
                            <button disabled class="bg-gray-300 text-gray-500 w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center cursor-not-allowed">
                                <i class="fa-solid fa-ban"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                    <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Belum ada produk best seller saat ini.</p>
                </div>
            @endforelse

        </div>
    </div>

    {{-- SECTION 3: PRODUK BARU --}}
    <div class="container mx-auto px-4 md:px-8 pb-12">
        <h2 class="font-bold text-xl md:text-2xl mb-6 text-gray-800">Produk Baru ✨</h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">

            {{-- LOOPING DATA BARANG --}}
            @forelse($new_products as $item)
                <div class="group bg-white border border-gray-100 rounded-xl p-3 md:p-4 shadow-sm hover:shadow-xl transition duration-300">

                    {{-- Gambar Produk --}}
                    <div class="relative aspect-square bg-gray-100 rounded-lg mb-3 overflow-hidden">
                        {{-- Logika Gambar: Gunakan file() method dari Fileable trait --}}
                        <img src="{{ $item->file('gambar')->hasFile() ? $item->file('gambar')->preview() : Vite::asset('resources/assets/photos/bakpia.jpg') }}" alt="{{ $item->nama_barang }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">

                        {{-- Badge New --}}
                        @if ($item->stok_ready < 1)
                            <span class="absolute top-2 right-2 bg-gray-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow">HABIS</span>
                        @elseif($item->stok_ready < 5)
                            <span class="absolute top-2 right-2 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow">HAMPIR HABIS</span>
                        @else
                            <span class="absolute top-2 right-2 bg-blue-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow">NEW</span>
                        @endif
                    </div>

                    {{-- Badge Info (Misal: Keterangan / Stok) --}}
                    <div class="mb-1">
                        <span class="text-[10px] md:text-xs text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded border border-green-100">
                            {{ $item->keterangan ?? 'Tahan 7 Hari' }}
                        </span>
                    </div>

                    {{-- Nama Produk --}}
                    <h3 class="font-bold text-gray-800 text-sm md:text-base mb-1 line-clamp-1" title="{{ $item->nama_barang }}">
                        {{ $item->nama_barang }}
                    </h3>

                    {{-- Harga & Button --}}
                    <div class="flex justify-between items-end mt-2">
                        <div>

                            <p class="text-amber-600 font-bold text-base md:text-lg">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- Button Add --}}
                        @if ($item->stok_ready > 0)
                            <button onclick="addToCart({{ $item->id }})" class="bg-gray-900 text-white w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center hover:bg-amber-600 transition shadow-lg transform active:scale-90">
                                <i class="fa-solid fa-cart-plus"></i>
                            </button>
                        @else
                            <button disabled class="bg-gray-300 text-gray-500 w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center cursor-not-allowed">
                                <i class="fa-solid fa-ban"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                {{-- State Kosong --}}
                <div class="col-span-full text-center py-20 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                    <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Belum ada produk baru saat ini.</p>
                </div>
            @endforelse

        </div>
    </div>
@endsection
