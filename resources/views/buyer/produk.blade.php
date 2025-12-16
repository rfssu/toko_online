@extends('buyer.layouts.app')

@section('content')

    {{-- CSS Tambahan untuk Hide Scrollbar --}}
    <style>
        .hide-scroll::-webkit-scrollbar {
            display: none;
        }

        .hide-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    {{-- SECTION 1: HEADER PRODUK --}}
    <div class="bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100">
        <div class="container mx-auto px-4 md:px-8 py-8 md:py-12">
            <div class="text-center">
                <span
                    class="bg-amber-600 text-white text-xs font-bold px-4 py-1.5 rounded-full mb-4 inline-block shadow-lg">
                    <i class="fa-solid fa-store"></i> Katalog Produk
                </span>
                <h1 class="text-3xl md:text-4xl font-bold leading-tight mb-3 text-gray-900">
                    Jelajahi Semua <span class="text-amber-600">Oleh-Oleh Jogja</span>
                </h1>
                <p class="text-gray-600 md:text-lg max-w-2xl mx-auto">
                    Temukan berbagai pilihan oleh-oleh khas Jogja dengan kualitas terbaik dan harga terjangkau
                </p>
            </div>
        </div>
    </div>

    {{-- SECTION 2: FILTER & SEARCH --}}
    <div class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm">
        <div class="container mx-auto px-4 md:px-8 py-4">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">

                {{-- Search Bar --}}
                <div class="w-full md:w-1/2 lg:w-1/3">
                    <form action="{{ route('produk') }}" method="GET">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari produk favorit..."
                                class="input input-bordered w-full rounded-full pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-amber-500 border-gray-300">
                            <button type="submit" class="absolute left-0 top-0 h-full px-4 hover:text-amber-600 transition">
                                <i class="fa-solid fa-search text-gray-400"></i>
                            </button>
                        </div>
                    </form>
                </div>



                {{-- Filter Buttons --}}
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('produk', ['filter' => 'popular', 'search' => request('search')]) }}"
                        class="btn btn-sm {{ (request('filter') ?? '') == 'popular' ? 'bg-amber-600 text-white hover:bg-amber-700' : 'bg-white hover:bg-amber-50 text-gray-700' }} border-gray-300 rounded-full">
                        <i
                            class="fa-solid fa-fire {{ (request('filter') ?? '') == 'popular' ? 'text-white' : 'text-amber-600' }}"></i>
                        Terpopuler
                    </a>
                    <a href="{{ route('produk', ['filter' => 'price_low', 'search' => request('search')]) }}"
                        class="btn btn-sm {{ (request('filter') ?? '') == 'price_low' ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-white hover:bg-green-50 text-gray-700' }} border-gray-300 rounded-full">
                        <i
                            class="fa-solid fa-arrow-down-1-9 {{ (request('filter') ?? '') == 'price_low' ? 'text-white' : 'text-green-600' }}"></i>
                        Harga Terendah
                    </a>
                    <a href="{{ route('produk', ['filter' => 'price_high', 'search' => request('search')]) }}"
                        class="btn btn-sm {{ (request('filter') ?? '') == 'price_high' ? 'bg-red-600 text-white hover:bg-red-700' : 'bg-white hover:bg-red-50 text-gray-700' }} border-gray-300 rounded-full">
                        <i
                            class="fa-solid fa-arrow-up-9-1 {{ (request('filter') ?? '') == 'price_high' ? 'text-white' : 'text-red-600' }}"></i>
                        Harga Tertinggi
                    </a>
                    <a href="{{ route('produk', ['filter' => 'newest', 'search' => request('search')]) }}"
                        class="btn btn-sm {{ (request('filter') ?? '') == 'newest' ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-white hover:bg-blue-50 text-gray-700' }} border-gray-300 rounded-full">
                        <i
                            class="fa-solid fa-clock {{ (request('filter') ?? '') == 'newest' ? 'text-white' : 'text-blue-600' }}"></i>
                        Terbaru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 md:px-8 py-4">
        <!-- pemberi jarak -->
    </div>

    {{-- SECTION 4: GRID PRODUK --}}
    <div class="container mx-auto px-4 md:px-8 pb-12">

        {{-- Info Jumlah Produk --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <p class="text-gray-600 text-sm md:text-base">
                    Menampilkan <span class="font-bold text-gray-900">{{ $models->count() }}</span> produk
                </p>
                @if(request('search'))
                    <div class="flex items-center gap-2 mt-2">
                        <p class="text-sm text-gray-500">
                            Hasil pencarian untuk: <span class="font-semibold text-amber-600">"{{ request('search') }}"</span>
                        </p>
                        <a href="{{ route('produk') }}" class="text-xs text-gray-500 hover:text-amber-600 underline">
                            Hapus filter
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Grid Produk --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">

            {{-- LOOPING DATA BARANG --}}
            @forelse($models as $item)
                <div
                    class="group bg-white border border-gray-100 rounded-xl p-3 md:p-4 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">

                    {{-- Gambar Produk --}}
                    <div class="relative aspect-square bg-gray-100 rounded-lg mb-3 overflow-hidden">
                        <img src="{{ $item->file('gambar')->hasFile() ? $item->file('gambar')->preview() : Vite::asset('resources/assets/photos/bakpia.jpg') }}"
                            alt="{{ $item->nama_barang }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500">



                        {{-- Quick Action Buttons --}}
                        <div
                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition duration-300 flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
                            <button
                                class="btn btn-sm btn-circle bg-white text-gray-900 border-none hover:bg-amber-600 hover:text-white shadow-lg transform scale-90 hover:scale-100 transition">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button
                                class="btn btn-sm btn-circle bg-white text-gray-900 border-none hover:bg-red-500 hover:text-white shadow-lg transform scale-90 hover:scale-100 transition">
                                <i class="fa-solid fa-heart"></i>
                            </button>
                        </div>
                    </div>


                    {{-- Nama Produk --}}
                    <h3 class="font-bold text-gray-800 text-sm md:text-base mb-1 line-clamp-2 min-h-[2.5rem]"
                        title="{{ $item->nama_barang }}">
                        {{ $item->nama_barang }}
                    </h3>

                    {{-- Keterangan Produk --}}
                    @if($item->keterangan)
                        <div class="mb-2">
                            <span
                                class="text-[10px] md:text-xs text-gray-600 font-medium bg-gray-50 px-2 py-0.5 rounded border border-gray-200">
                                {{ $item->keterangan }}
                            </span>
                        </div>
                    @endif



                    {{-- Harga & Button --}}
                    <div class="flex justify-between items-end mt-3 pt-3 border-t border-gray-100">
                        <div>

                            <p class="text-amber-600 font-bold text-base md:text-lg">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- Button Add to Cart --}}
                        <button onclick="addToCart({{ $item->id }})"
                            class="bg-gray-900 text-white w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center hover:bg-amber-600 transition shadow-lg transform active:scale-90">
                            <i class="fa-solid fa-cart-plus"></i>
                        </button>
                    </div>
                </div>
            @empty
                {{-- State Kosong --}}
                <div class="col-span-full text-center py-20 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                    <i class="fa-solid fa-box-open text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Produk Tidak Ditemukan</h3>
                    <p class="text-gray-500 mb-4">Belum ada produk yang tersedia saat ini.</p>
                    <a href="/" class="btn bg-amber-600 hover:bg-amber-700 text-white border-none rounded-full px-6">
                        <i class="fa-solid fa-home"></i> Kembali ke Beranda
                    </a>
                </div>
            @endforelse

        </div>

        {{-- Pagination --}}
        @if($models->hasPages())
            <div class="flex justify-center mt-12">
                {{ $models->appends(request()->only(['search', 'filter']))->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>

@endsection