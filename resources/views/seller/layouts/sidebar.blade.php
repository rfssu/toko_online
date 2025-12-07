<div class="drawer-side">
    <label for="main-drawer" class="drawer-overlay"></label>
    <aside class="bg-base-200 w-80 h-screen flex flex-col">
        <!-- Logo Area - Fixed at top -->
        <div class="shrink-0 z-[51] items-center gap-2 bg-base-200 px-4 py-2 backdrop-blur flex shadow-sm">
            <a href="{{ route('dashboard') }}" class="flex-0 btn btn-ghost px-2">
                <div class="font-title inline-flex text-lg md:text-2xl">
                    <span class="text-primary">{{ config('app.name') }}</span>
                </div>
            </a>
        </div>

        <!-- Middle Content - Scrollable Area -->
        <div class="flex-1 overflow-hidden flex flex-col">
            <div class="flex-1 px-4 overflow-y-auto scrollbar-thin">
                <!-- Navigation Menu -->
                <ul class="menu menu-lg gap-2 font-medium pt-4">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="flex gap-4 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="ri-home-2-line"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="mt-4">
                        <h2 class="menu-title text-base-content/60 pl-4">
                            <span>Menu Utama</span>
                        </h2>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}"
                            class="flex gap-4 {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="ri-group-line"></i>
                            Olah Users
                        </a>
                    </li>
                    <li class="mt-4">
                        <h2 class="menu-title text-base-content/60 pl-4">
                            <span>Pengaturan</span>
                        </h2>
                    </li>
                    <li>
                        <a href="#" class="flex gap-4">
                            <i class="ri-id-card-line"></i>
                            Edit Profil
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- User Info - Fixed at bottom -->
        <div class="shrink-0 bg-base-200 px-4 py-4 border-t border-base-300">
            <div class="flex items-center gap-4 p-4 bg-base-100 rounded-lg shadow-sm">
                <div class="avatar">
                    <div class="w-10 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=random" />
                    </div>
                </div>
                <div>
                    <p class="font-medium">{{ Auth::user()->name }}</p>
                    <p class="text-sm opacity-70">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>
    </aside>
</div>
