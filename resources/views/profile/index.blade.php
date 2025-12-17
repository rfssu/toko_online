@extends('seller/layouts/main')

@section('breadcrumb')
    <a href="{{ route('setting.profile.index') }}" class="flex items-center gap-2">
        Edit Profil
    </a>
@endsection

@section('pages')
    <!-- Profile Card -->
    <div class="card bg-base-100 shadow-lg">
        <div class="card-body">

            <!-- Edit Form -->
            <h3 class="font-semibold text-lg mb-4">
                <i class="ri-edit-line mr-2"></i>Edit Profile
            </h3>

            <form id="form-elem" action="{{ route('setting.profile.update', $model->id) }}" method="POST" class="space-y-4 max-w-xl">
                @csrf
                @method('PUT')

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Name</span>
                    </label>
                    <input type="text" name="name" class="input input-bordered">

                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Email</span>
                    </label>
                    <input type="email" name="email" class="input input-bordered">

                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Phone</span>
                    </label>
                    <input type="text" name="no_hp" class="input input-bordered ">

                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Address</span>
                    </label>
                    <textarea name="alamat" class="textarea textarea-bordered h-24">{{ $model->alamat }}</textarea>

                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Password</span>
                        <span class="label-text-alt text-gray-500">Kosongkan jika tidak ingin mengubah</span>
                    </label>
                    <input type="password" name="password" class="input input-bordered">

                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Confirm Password</span>
                    </label>
                    <input type="password" name="password_confirmation" class="input input-bordered">
                </div>

                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line mr-2"></i>
                        Update Profile
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
    <script id="data-json" type="application/json">
    {!! $model->toJson(JSON_FORCE_OBJECT) !!}
</script>

    <script type="module">
        jsonScriptToFormFields('#form-elem', '#data-json');
        $('#form-elem').formAjaxSubmit();
    </script>
@endpush
