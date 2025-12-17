<!-- resources/views/layouts/partials/header.blade.php -->
<header class="sticky top-0 z-[49] bg-base-200 shadow-lg backdrop-blur supports-backdrop-blur:bg-base-200/60">
    <div class="navbar container mx-2 flex justify-between">
        <!-- Tombol Menu Mobile -->
        <div class="flex-none lg:hidden">
            <label for="main-drawer" class="btn btn-square btn-ghost drawer-button">
                <i class="ri-menu-line text-xl"></i>
            </label>
        </div>

        <!-- Jam -->
        <div class="hidden lg:flex">
            <div class="flex items-center gap-2 text-2xl font-bold">
                <!-- Icon Jam -->
                <i class="ri-time-line "></i>
                <!-- Jam Digital -->
                <div class="flex items-center gap-1">
                    <span id="clock" class="font-mono bg-base-100 px-3 py-1 rounded-lg shadow-sm">00:00:00</span>
                    <span class="text-xs font-normal text-base-content/70">WIB</span>
                </div>
            </div>
        </div>

        <!-- Menu Kanan -->
        <div class="flex-none gap-5">
            <!-- Toggle Tema dengan Animasi -->
            <div class="tooltip tooltip-bottom mr-2" data-tip="Ganti Tema">
                <label class="swap swap-rotate btn btn-ghost btn-circle">
                    <input type="checkbox" class="theme-controller swap-input" value="dark" />
                    <i class="ri-moon-line swap-on text-xl"></i>
                    <i class="ri-sun-line swap-off text-xl"></i>
                </label>
            </div>
            <!-- Profil Dropdown -->
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
                <ul tabindex="0" class="menu dropdown-content z-[1] p-2 shadow-xl bg-base-200 rounded-box w-52 mt-4">
                    <li class="menu-title">
                        <span class="text-base-content">{{ Auth::user()->name }}</span>
                        <small class="text-muted">{{ Auth::user()->role }}</small>
                    </li>
                    <div class="divider my-0"></div>
                    <li>
                        <a href="{{ route('setting.profile.index') }}" class="flex items-center gap-2">
                            <i class="ri-user-line"></i>
                            Profil
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2 text-error">
                                <i class="ri-logout-box-r-line"></i>
                                Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
