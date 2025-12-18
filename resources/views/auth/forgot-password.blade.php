@extends('auth.layout')

@section('content')
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            {{-- Logo/Header --}}
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">🔐</h1>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Lupa Password?</h2>
                <p class="text-gray-600">
                    Masukkan email Anda untuk menerima link reset password
                </p>
            </div>

            {{-- Success Message --}}
            @if (session('status'))
                <div class="alert alert-success mb-6">
                    <i class="fa-solid fa-check-circle"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            {{-- Form --}}
            <div class="card bg-base-100 shadow-2xl">
                <div class="card-body">
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        {{-- Email Input --}}
                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">
                                    <i class="fa-solid fa-envelope text-amber-600"></i> Email
                                </span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com"
                                required autofocus
                                class="input input-bordered w-full @error('email') input-error @enderror">
                            @error('email')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <div class="form-control mt-6">
                            <button type="submit" class="btn bg-amber-600 hover:bg-amber-700 text-white border-none w-full">
                                <i class="fa-solid fa-paper-plane"></i>
                                Kirim Link Reset Password
                            </button>
                        </div>
                    </form>

                    {{-- Back to Login --}}
                    <div class="divider">ATAU</div>
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="link link-hover text-amber-600 font-semibold">
                            <i class="fa-solid fa-arrow-left"></i>
                            Kembali ke Login
                        </a>
                    </div>
                </div>
            </div>

            {{-- Footer Info --}}
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    <i class="fa-solid fa-info-circle"></i>
                    Link reset password akan dikirim ke email Anda dan berlaku selama 60 menit
                </p>
            </div>
        </div>
    </div>
@endsection