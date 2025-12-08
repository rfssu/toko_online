@extends('seller/layouts/main')

@section('breadcrumb')
    <a href="{{ route('users.index') }}" class="flex items-center gap-2">
        Olah User
    </a>
@endsection
@php
    $createRoute = fn() => route('users.create');
    $editRoute = fn($model) => route('users.edit', $model->id);
    $deleteRoute = fn($model) => route('users.destroy', $model->id);
@endphp
@section('pages')
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h1 class="card-title text-lg "><b>Daftar Users</b></h1>
                <a href="{{ $createRoute() }}" onclick="modalFormAjax(this, event)" class="btn btn-primary">
                    <i class="ri-add-line mr-2"></i>
                    Tambah User
                </a>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <!-- head -->
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Aksi</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>No HP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @forelse ($models as $model)
                            <tr>
                                <td>{{ $no++ }}</td>
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
        </div>
    </div>
@endsection
