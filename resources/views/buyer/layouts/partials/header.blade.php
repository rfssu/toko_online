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
                    <li><a>Produk</a></li>
                    <li><a>Tentang Kami</a></li>
                </ul>
            </div>
            
            {{-- Logo Toko --}}
            <a href="{{ url('/') }}" class="btn btn-ghost text-xl md:text-2xl font-bold tracking-tight text-amber-600 hover:bg-transparent px-2">
                <i class="fa-solid fa-store mr-1"></i> Khas<span class="text-gray-800">Jogja.</span>
            </a>
            
            {{-- Menu Desktop (Sebelah Logo) --}}
            <ul class="menu menu-horizontal px-1 hidden lg:flex font-medium text-gray-600 gap-2 ml-4">
                <li><a href="{{ url('/') }}" class="hover:text-amber-600 hover:bg-amber-50 rounded-lg">Beranda</a></li>
                <li><a class="hover:text-amber-600 hover:bg-amber-50 rounded-lg">Produk</a></li>
                <li><a class="hover:text-amber-600 hover:bg-amber-50 rounded-lg">Tentang Kami</a></li>
            </ul>
        </div>

        {{-- BAGIAN TENGAH (Kosong untuk spacer) --}}
        <div class="navbar-center hidden lg:flex"></div>

        {{-- BAGIAN KANAN: Search, Cart, Profile --}}
        <div class="navbar-end flex-1 gap-2 md:gap-4">
            
            {{-- Search Bar (Desktop) --}}
            <div class="form-control hidden md:block w-full max-w-xs">
                <div class="relative">
                    <input type="text" placeholder="Cari bakpia, keripik..." class="input input-bordered input-sm w-full rounded-full bg-gray-100 focus:outline-none focus:border-amber-500 pr-10" />
                    <i class="fa-solid fa-magnifying-glass absolute right-3 top-2.5 text-gray-400 text-xs"></i>
                </div>
            </div>
            
            {{-- Search Icon (Mobile Only) --}}
            <button class="btn btn-ghost btn-circle btn-sm md:hidden">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>

            {{-- Cart Icon dengan Badge --}}
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                    <div class="indicator">
                        <i class="fa-solid fa-cart-shopping text-xl text-gray-700"></i>
                        <span class="badge badge-sm badge-error text-white indicator-item border-none">0</span>
                    </div>
                </div>
                {{-- Cart Preview Dropdown --}}
                <div tabindex="0" class="mt-3 z-[1] card card-compact dropdown-content w-52 bg-base-100 shadow-xl border border-gray-100">
                    <div class="card-body">
                        <span class="font-bold text-lg">0 Barang</span>
                        <span class="text-info text-amber-600">Subtotal: Rp 0</span>
                        <div class="card-actions">
                            <button class="btn bg-amber-600 hover:bg-amber-700 text-white btn-block btn-sm border-none">Lihat Keranjang</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Profile Icon --}}
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar border border-gray-200 hover:border-amber-500 transition">
                    <div class="w-9 rounded-full">
                        {{-- Placeholder Avatar --}}
                        <img alt="User" src="https://ui-avatars.com/api/?name=User&background=random" />
                    </div>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52 border border-gray-100">
                    <li>
                        <a class="justify-between">
                            Profile
                            <span class="badge badge-ghost text-xs">New</span>
                        </a>
                    </li>
                    <li><a>Riwayat Pesanan</a></li>
                    <li><a>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>