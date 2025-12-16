<div class="sticky top-0 z-50 w-full">
    <div class="navbar bg-base-100/95 backdrop-blur border-b border-gray-100 shadow-sm px-4 md:px-8 h-20">

        {{-- BAGIAN KIRI: Toggle Mobile & Logo --}}
        <div class="navbar-start w-auto lg:w-1/2">
            <div class="dropdown">
                <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                    <i class="fa-solid fa-bars text-xl"></i>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li><a href="{{ url('/produk') }}">Produk</a></li>
                    <li><a href="{{ url('/tentang-kami') }}">Tentang Kami</a></li>
                </ul>
            </div>

            {{-- Logo Toko --}}
            <a href="{{ url('/') }}" class="btn btn-ghost text-xl md:text-2xl font-bold tracking-tight text-amber-600 hover:bg-transparent px-2">
                <i class="fa-solid fa-store mr-1"></i> Khas<span class="text-gray-800">Jogja.</span>
            </a>

            {{-- Menu Desktop (Sebelah Logo) --}}
            <ul class="menu menu-horizontal px-1 hidden lg:flex font-medium text-gray-600 gap-2 ml-4">
                <li><a href="{{ url('/') }}" class="hover:text-amber-600 hover:bg-amber-50 rounded-lg">Beranda</a></li>
                <li><a href="{{ url('/produk') }}" class="hover:text-amber-600 hover:bg-amber-50 rounded-lg">Produk</a>
                </li>
                <li><a href="{{ url('/tentang-kami') }}" class="hover:text-amber-600 hover:bg-amber-50 rounded-lg">Tentang Kami</a></li>
            </ul>
        </div>

        {{-- BAGIAN TENGAH (Kosong untuk spacer) --}}
        <div class="navbar-center hidden lg:flex"></div>

        {{-- BAGIAN KANAN: Search, Cart, Profile --}}
        <div class="navbar-end flex-1 gap-2 md:gap-4">

            {{-- Search Bar (Desktop) --}}
            <form action="{{ route('produk') }}" method="GET" class="form-control hidden md:block w-full max-w-xs">
                <div class="relative">
                    <input type="text" name="search" placeholder="Cari bakpia, keripik..." value="{{ request('search') }}" class="input input-bordered input-sm w-full rounded-full bg-gray-100 focus:outline-none focus:border-amber-500 pr-10" />
                    <button type="submit" class="absolute right-0 top-0 h-full px-3 hover:text-amber-600 transition">
                        <i class="fa-solid fa-magnifying-glass text-gray-400 text-xs"></i>
                    </button>
                </div>
            </form>

            {{-- Search Icon (Mobile Only) - Opens Modal --}}
            <button onclick="document.getElementById('mobile_search_modal').showModal()" class="btn btn-ghost btn-circle btn-sm md:hidden">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>

            @php
                $user = Auth::user();
                $keranjang = $user?->barang_keranjang ?? collect();

                $cart_count = $keranjang->count();
                $cart_total = $keranjang->sum('total_keranjang');
            @endphp
            {{-- Cart Icon dengan Badge --}}
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                    <div class="indicator">
                        <i class="fa-solid fa-cart-shopping text-xl text-gray-700"></i>
                        <span class="badge badge-sm badge-error text-white indicator-item border-none" id="cart-badge">
                            {{ $cart_count }}
                        </span>
                    </div>
                </div>
                <div tabindex="0" class="mt-3 z-[1] card card-compact dropdown-content w-52 bg-base-100 shadow-xl border">
                    <div class="card-body">
                        @auth
                            <span class="font-bold text-lg" id="cart-count-text">{{ $cart_count }} Barang</span>
                            <span class="text-amber-600" id="cart-total-text">
                                Subtotal: Rp {{ number_format($cart_total, 0, ',', '.') }}
                            </span>
                            <div class="card-actions">
                                <a href="{{ route('checkout') }}" class="btn bg-amber-600 hover:bg-amber-700 text-white btn-block btn-sm border-none">
                                    Lihat Keranjang
                                </a>
                            </div>
                        @else
                            <span class="font-bold text-lg mb-5 text-center">Anda belum login</span>
                            <div class="card-actions">
                                <a href="{{ route('login') }}" class="btn bg-amber-600 hover:bg-amber-700 text-white btn-block btn-sm border-none">
                                    Login
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- Profile Icon --}}
            @auth
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="btn btn-ghost btn-circle avatar online">
                        <div class="w-10 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                            @php
                                $initials = strtoupper(substr(Auth::user()->name, 0, 2));
                                $colors = ['bg-red-500', 'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-teal-500'];
                                $bg = $colors[ord($initials) % count($colors)];
                            @endphp

                            <div class="w-10 h-10 rounded-full {{ $bg }} flex items-center justify-center text-white font-bold">
                                {{ $initials }}
                            </div>
                        </div>
                    </label>
                    <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52 border border-gray-100">
                        <li>
                            <a href="{{ route('profile') }}" class="justify-between">
                                Profile
                                <span class="badge badge-ghost text-xs">New</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('history') }}" class="justify-between">
                                Riwayat Pesanan
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">@csrf
                                <button type="submit">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline btn-sm rounded-full">
                    Login
                </a>
            @endauth
        </div>
    </div>
</div>

{{-- Mobile Search Modal --}}
<dialog id="mobile_search_modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Cari Produk</h3>
        <form action="{{ route('produk') }}" method="GET">
            <div class="relative">
                <input type="text" name="search" placeholder="Cari bakpia, keripik..." autofocus class="input input-bordered w-full rounded-full pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-amber-500" />
                <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
            <div class="modal-action">
                <button type="button" onclick="document.getElementById('mobile_search_modal').close()" class="btn btn-ghost">Batal</button>
                <button type="submit" class="btn bg-amber-600 hover:bg-amber-700 text-white border-none">
                    <i class="fa-solid fa-search"></i> Cari
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
