@extends('buyer.layouts.app')

@section('content')

    {{-- pemberi jarak --}}
    <div class="container mx-auto px-4 md:px-8 py-4">

    </div>
    @if ($errors->any())
        <div class="container mx-auto px-4 md:px-8 mb-4">
            <div class="alert alert-error shadow-lg">
                <div>
                    <i class="fa-solid fa-exclamation-circle"></i>
                    <div>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif


    {{-- SECTION 3: KONTEN PROFIL --}}
    <div class="container mx-auto px-4 md:px-8 pb-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">

            {{-- SIDEBAR MENU --}}
            <div class="lg:col-span-1">
                <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm">

                    {{-- User Info --}}
                    <div class="flex flex-col items-center mb-6 pb-6 border-b border-gray-100">
                        <div class="w-20 h-20 md:w-24 md:h-24 bg-gradient-to-br from-amber-600 to-orange-600 rounded-full flex items-center justify-center mb-3 shadow-lg">
                            <i class="fa-solid fa-user text-white text-3xl md:text-4xl"></i>
                        </div>
                        <h3 class="text-lg md:text-xl font-bold text-gray-900">{{ $user->name ?? 'Nama Belum Diisi' }}</h3>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>

                    {{-- Menu Navigasi --}}
                    <nav class="space-y-2">
                        <a href="#" class="flex items-center gap-3 px-4 py-3 bg-amber-50 text-amber-700 rounded-lg font-semibold group transition">
                            <i class="fa-solid fa-user text-lg"></i>
                            <span>Informasi Profil</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg font-medium group transition">
                            <i class="fa-solid fa-shopping-bag text-lg"></i>
                            <span>Pesanan Saya</span>
                        </a>
                        <a href="#password-section" onclick="showPasswordSection()" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg font-medium group transition">
                            <i class="fa-solid fa-lock text-lg"></i>
                            <span>Ubah Password</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg font-medium group transition">
                                <i class="fa-solid fa-sign-out-alt text-lg"></i>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            {{-- KONTEN UTAMA --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- INFORMASI DASAR --}}
                <div id="profile-section" class="bg-white border border-gray-100 rounded-xl p-6 md:p-8 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl md:text-2xl font-bold text-gray-900">Informasi Dasar</h2>
                        <button onclick="toggleEditMode()" id="edit-btn" class="btn btn-sm bg-amber-600 hover:bg-amber-700 text-white border-none rounded-full">
                            <i class="fa-solid fa-edit"></i> Edit
                        </button>
                    </div>
                    {{-- VIEW MODE --}}
                    <div id="view-mode" class="space-y-5">
                        {{-- Nama Lengkap --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            <label class="text-sm font-semibold text-gray-700">Nama Lengkap</label>
                            <div class="md:col-span-2">
                                <p class="text-gray-900 bg-gray-50 px-4 py-2.5 rounded-lg border border-gray-200">
                                    {{ $user->name ?? '-' }}</p>
                            </div>
                        </div>
                        {{-- Email --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            <label class="text-sm font-semibold text-gray-700">Email</label>
                            <div class="md:col-span-2">
                                <p class="text-gray-900 bg-gray-50 px-4 py-2.5 rounded-lg border border-gray-200">
                                    {{ $user->email }}</p>
                            </div>
                        </div>
                        {{-- No. Telepon --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            <label class="text-sm font-semibold text-gray-700">No. Telepon</label>
                            <div class="md:col-span-2">
                                <p class="text-gray-900 bg-gray-50 px-4 py-2.5 rounded-lg border border-gray-200">
                                    {{ $user->no_hp ?? 'Belum Diisi' }}</p>
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            <label class="text-sm font-semibold text-gray-700">Alamat</label>
                            <div class="md:col-span-2">
                                <p class="text-gray-900 bg-gray-50 px-4 py-2.5 rounded-lg border border-gray-200">
                                    {{ $user->alamat ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                    {{-- EDIT MODE --}}
                    <form id="edit-mode" action="{{ route('profile.update') }}" method="POST" class="space-y-5" style="display: none;">
                        @csrf

                        {{-- Nama Lengkap --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            <label class="text-sm font-semibold text-gray-700">Nama Lengkap</label>
                            <div class="md:col-span-2">
                                <input type="text" name="name" value="{{ $user->name }}" class="input input-bordered w-full rounded-lg" required>
                            </div>
                        </div>
                        {{-- Email --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            <label class="text-sm font-semibold text-gray-700">Email</label>
                            <div class="md:col-span-2">
                                <input type="email" name="email" value="{{ $user->email }}" class="input input-bordered w-full rounded-lg" required>
                            </div>
                        </div>
                        {{-- No. Telepon --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            <label class="text-sm font-semibold text-gray-700">No. Telepon</label>
                            <div class="md:col-span-2">
                                <input type="text" name="no_hp" value="{{ $user->no_hp }}" class="input input-bordered w-full rounded-lg" placeholder="Contoh: 081234567890">
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            <label class="text-sm font-semibold text-gray-700">Alamat</label>
                            <div class="md:col-span-2">
                                <textarea name="alamat" rows="3" class="textarea textarea-bordered w-full rounded-lg" placeholder="Alamat lengkap">{{ $user->alamat }}</textarea>
                            </div>
                        </div>
                        {{-- Tombol Aksi --}}
                        <div class="flex justify-end gap-2 mt-6">
                            <button type="button" onclick="toggleEditMode()" class="btn btn-sm bg-gray-500 hover:bg-gray-600 text-white border-none rounded-full">
                                <i class="fa-solid fa-times"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-sm bg-green-600 hover:bg-green-700 text-white border-none rounded-full">
                                <i class="fa-solid fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
                {{-- UBAH PASSWORD --}}
                <div id="password-section" class="bg-white border border-gray-100 rounded-xl p-6 md:p-8 shadow-sm" style="display: none;">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl md:text-2xl font-bold text-gray-900">Ubah Password</h2>
                        <button onclick="hidePasswordSection()" class="btn btn-sm bg-gray-500 hover:bg-gray-600 text-white border-none rounded-full">
                            <i class="fa-solid fa-times"></i> Tutup
                        </button>
                    </div>
                    <form action="{{ route('profile.password') }}" method="POST" class="space-y-5">
                        @csrf

                        {{-- Password Lama --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            <label class="text-sm font-semibold text-gray-700">Password Lama</label>
                            <div class="md:col-span-2">
                                <input type="password" name="current_password" class="input input-bordered w-full rounded-lg" required placeholder="Masukkan password lama">
                            </div>
                        </div>
                        {{-- Password Baru --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            <label class="text-sm font-semibold text-gray-700">Password Baru</label>
                            <div class="md:col-span-2">
                                <input type="password" name="new_password" class="input input-bordered w-full rounded-lg" required placeholder="Minimal 8 karakter">
                            </div>
                        </div>
                        {{-- Konfirmasi Password Baru --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            <label class="text-sm font-semibold text-gray-700">Konfirmasi Password Baru</label>
                            <div class="md:col-span-2">
                                <input type="password" name="new_password_confirmation" class="input input-bordered w-full rounded-lg" required placeholder="Ulangi password baru">
                            </div>
                        </div>
                        {{-- Tombol Submit --}}
                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-sm bg-amber-600 hover:bg-amber-700 text-white border-none rounded-full">
                                <i class="fa-solid fa-key"></i> Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle antara View Mode dan Edit Mode
        function toggleEditMode() {
            const viewMode = document.getElementById('view-mode');
            const editMode = document.getElementById('edit-mode');
            const editBtn = document.getElementById('edit-btn');

            if (viewMode.style.display === 'none') {
                viewMode.style.display = 'block';
                editMode.style.display = 'none';
                editBtn.innerHTML = '<i class="fa-solid fa-edit"></i> Edit';
            } else {
                viewMode.style.display = 'none';
                editMode.style.display = 'block';
                editBtn.style.display = 'none';
            }
        }
        // Show Password Section
        function showPasswordSection() {
            document.getElementById('password-section').style.display = 'block';
            document.getElementById('profile-section').scrollIntoView({
                behavior: 'smooth'
            });
        }
        // Hide Password Section
        function hidePasswordSection() {
            document.getElementById('password-section').style.display = 'none';
        }
        // Auto hide success message after 3 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 3000);
    </script>

@endsection
