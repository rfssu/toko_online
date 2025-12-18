@extends('auth.layout')

@section('content')
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            {{-- Logo/Header --}}
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">🔑</h1>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Reset Password</h2>
                <p class="text-gray-600">
                    Masukkan password baru untuk akun Anda
                </p>
            </div>

            {{-- Form --}}
            <div class="card bg-base-100 shadow-2xl">
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        {{-- Email (readonly) --}}
                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">
                                    <i class="fa-solid fa-envelope text-amber-600"></i> Email
                                </span>
                            </label>
                            <input type="email" name="email" value="{{ $email ?? old('email') }}" readonly
                                class="input input-bordered w-full bg-base-200">
                        </div>

                        {{-- New Password --}}
                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">
                                    <i class="fa-solid fa-lock text-amber-600"></i> Password Baru
                                </span>
                            </label>
                            <input type="password" name="password" placeholder="Minimal 8 karakter" required
                                class="input input-bordered w-full @error('password') input-error @enderror">
                            @error('password')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">
                                    <i class="fa-solid fa-lock text-amber-600"></i> Konfirmasi Password
                                </span>
                            </label>
                            <input type="password" name="password_confirmation" placeholder="Ketik ulang password baru"
                                required class="input input-bordered w-full">
                        </div>

                        {{-- Submit Button --}}
                        <div class="form-control mt-6">
                            <button type="submit" class="btn bg-amber-600 hover:bg-amber-700 text-white border-none w-full">
                                <i class="fa-solid fa-check"></i>
                                Reset Password
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

            {{-- Security Notice --}}
            <div class="alert alert-info mt-6">
                <i class="fa-solid fa-shield-halved"></i>
                <span class="text-sm">
                    Pastikan password baru Anda kuat dan tidak mudah ditebak
                </span>
            </div>
        </div>
    </div>
@endsection