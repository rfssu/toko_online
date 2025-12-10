@extends('seller/layouts/main')

@section('breadcrumb')
    <a href="{{ route('users.index') }}" class="flex items-center gap-2">
        Olah User
    </a>
@endsection
@php
    $indexRoute = fn() => route('users.index');
    $createRoute = fn() => route('users.create');
    $editRoute = fn($model) => route('users.edit', $model->id);
    $deleteRoute = fn($model) => route('users.destroy', $model->id);

    use App\Models\User;
@endphp
@section('pages')
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h1 class="card-title text-lg "><b>Daftar Users</b></h1>

            </div>

            <form method="GET" action="{{ $indexRoute() }}" class="mb-4">
                <div class="flex flex-col sm:flex-row gap-2 w-full justify-between">
                    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                        <a href="{{ $createRoute() }}" onclick="modalFormAjax(this, event)"
                            class="btn btn-primary w-full sm:w-auto">
                            <i class="ri-add-line mr-2"></i>
                            Tambah User
                        </a>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                        {{ $models->links('seller/components/per-page') }}
                        <div class="form-control">
                            <select name="role" class="select select-bordered w-full" onchange="this.form.submit()">
                                <option value="">Semua Role</option>
                                @foreach (User::ROLE as $key => $label)
                                    <option value="{{ $key }}" {{ request('role') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control">
                            <select name="status" class="select select-bordered w-full" onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                @foreach (User::STATUS as $key => $label)
                                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{ $models->links('seller/components/search', get_defined_vars()) }}
                    </div>
                </div>
            </form>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <!-- head -->
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Aksi</th>
                            <x-sort-th column="name" label="Nama" />
                            <x-sort-th column="email" label="Email" />
                            <x-sort-th column="role" label="Role" />
                            <x-sort-th column="status" label="Status" />
                            <x-sort-th column="no_hp" label="No HP" />
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($models as $index => $model)
                            <tr>
                                <td>{{ $models->firstItem() + $index }}</td>
                                <td>
                                    <a href="{{ $deleteRoute($model) }}" onclick="modalDeleteConfirm(this, event)"
                                        data-name="{{ $model->name }}" class="btn btn-ghost btn-sm">
                                        <i class="ri-delete-bin-line"></i>
                                    </a>
                                    <a href="{{ $editRoute($model) }}" onclick="modalFormAjax(this, event)"
                                        class="btn btn-ghost btn-sm">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                </td>
                                <td>{{ $model->name }}</td>
                                <td>{{ $model->email }}</td>
                                <td>{{ $model->role_val }}</td>
                                <td>{{ $model->status_val }}</td>
                                <td>{{ $model->no_hp }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-10">
                {{ $models->links('seller/components/paginate') }}
            </div>
        </div>
    </div>
@endsection
