@php
    if (empty($model->id)) {
        $formHref = fn() => route('users.store');
    } else {
        $formHref = fn($model) => route('users.update', $model->id);
    }
@endphp

<div class="p-6">
    <h2 class="text-xl font-bold mb-4">{{ empty($model->id) ? 'Tambah User' : 'Edit User' }}</h2>

    <form method="POST" action="{{ $formHref($model) }}" id="form-elem" class="space-y-3">
        @csrf
        @if (!empty($model->id))
            @method('PUT')
        @endif

        <input type="text" name="name" placeholder="Nama" class="input input-bordered w-full max-w-xs" required />

        <input type="email" name="email" placeholder="Email" class="input input-bordered w-full max-w-xs"
            required />

        <select name="role" class="select select-bordered w-full" required>
            <option value="">Pilih Role</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>

        <select name="status" class="select select-bordered w-full" required>
            <option value="">Pilih Status</option>
            <option value="active">Aktif</option>
            <option value="inactive">Tidak Aktif</option>
        </select>

        <input type="tel" name="no_hp" placeholder="No HP" class="input input-bordered w-full max-w-xs" />

        <textarea name="alamat" placeholder="Alamat" class="textarea textarea-bordered w-full"></textarea>

        <input type="password" name="password" placeholder="Password" class="input input-bordered w-full max-w-xs"
            {{ empty($model->id) ? 'required' : '' }} />

        <input type="password" name="password_confirmation" placeholder="Ulangi Password"
            class="input input-bordered w-full max-w-xs" {{ empty($model->id) ? 'required' : '' }} />
            

        <button type="submit" class="btn btn-primary w-full">
            {{ empty($model->id) ? 'Simpan' : 'Update' }}
        </button>
    </form>
</div>

<script id="data-json" type="application/json">
    {!! $model->toJson(JSON_FORCE_OBJECT) !!}
</script>

<script type="module">
    jsonScriptToFormFields('#form-elem', '#data-json');
    $('#form-elem').formAjaxSubmit();
</script>
