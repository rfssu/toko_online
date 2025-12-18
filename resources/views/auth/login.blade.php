@extends('auth.layout')

@section('content')
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title justify-center mb-4">Silakan Masuk</h2>

            @if($errors->any())
                <div class="alert alert-error mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li class="text-xs">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.perform') }}" method="POST">
                @csrf

                <div class="form-control">
                    <label class="label"><span class="label-text">Email Address</span></label>
                    <input type="email" name="email" placeholder="email@anda.com" class="input input-bordered" required
                        autofocus />
                </div>

                <div class="form-control mt-4">
                    <label class="label"><span class="label-text">Password</span></label>
                    <input type="password" name="password" placeholder="******" class="input input-bordered" required />

                    <label class="label">
                        <a href="{{ route('password.request') }}" class="label-text-alt link link-hover text-amber-600">
                            <i class="fa-solid fa-key"></i> Lupa password?
                        </a>
                    </label>
                </div>

                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>

            <div class="divider">ATAU</div>

            <div class="text-center">
                <a href="{{ route('register') }}" class="btn btn-link btn-sm">Daftar Akun Baru</a>
            </div>
        </div>
    </div>
@endsection