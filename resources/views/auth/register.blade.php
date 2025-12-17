@extends('auth.layout')

@section('content')
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title justify-center mb-4">Silakan Daftar</h2>

            <form id="form-elem" action="{{ route('register.perform') }}" method="POST">
                @csrf
                <div class="form-control">
                    <label class="label"><span class="label-text">Nama Lengkap</span></label>
                    <input type="text" name="name" placeholder="Nama Lengkap" class="input input-bordered" autofocus />
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text">Email Address</span></label>
                    <input type="email" name="email" placeholder="Email Address" class="input input-bordered" autofocus />
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text">No Handphone</span></label>
                    <input type="text" name="no_hp" placeholder="No Handphone" class="input input-bordered" autofocus />
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text">Password</span></label>
                    <input type="password" name="password" placeholder="******" class="input input-bordered" />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Confirm Password</span></label>
                    <input type="password" name="password_confirmation" placeholder="******" class="input input-bordered" />
                </div>

                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">Daftar</button>
                </div>
            </form>

            <div class="divider">ATAU</div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="btn btn-link btn-sm">Masuk Akun Yang Sudah ada</a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        $('#form-elem').formAjaxSubmit();
    </script>
@endpush
