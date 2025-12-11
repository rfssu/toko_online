@extends('seller/layouts/main')

@section('breadcrumb')
    <a href="{{ route('barangs.index') }}" class="flex items-center gap-2">
        Olah Barang
    </a>
@endsection
@php
    $createRoute = fn() => route('barangs.create');
    $editRoute = fn($model) => route('barangs.edit', $model->id);
    $deleteRoute = fn($model) => route('barangs.destroy', $model->id);
    $indexRoute = fn() => route('barangs.index');
@endphp
@section('pages')
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h1 class="card-title text-lg "><b>Daftar Barang</b></h1>
            </div>

            <!-- Action Bar -->
            <form method="GET" action="{{ $indexRoute() }}" class="mb-4">
                <div class="flex flex-col sm:flex-row gap-2 w-full justify-between">
                    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                        <a href="{{ $createRoute() }}" onclick="modalFormAjax(this, event)"
                            class="btn btn-primary w-full sm:w-auto">
                            <i class="ri-add-line mr-2"></i>
                            Tambah Barang
                        </a>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                        {{ $models->links('seller/components/per-page') }}
                        {{ $models->links('seller/components/search', get_defined_vars()) }}
                    </div>
                </div>
            </form>



            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <!--     head -->
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Aksi</th>
                            <th>Nama Barang</th>
                            <th>Gambar</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Keterangan</th>
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
                                        data-name="{{ $model->nama_barang }}" class="btn btn-ghost btn-sm">
                                        <i class="ri-delete-bin-line"></i>
                                    </a>
                                    <a href="{{ $editRoute($model) }}" onclick="modalFormAjax(this, event)"
                                        class="btn btn-ghost btn-sm">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                </td>
                                <td>{{ $model->nama_barang }}</td>
                                <td>
                                    @if($model->file('gambar')->hasFile())
                                        <div class="avatar">
                                            <div class="w-16 h-16 rounded mb-2">
                                                <img src="{{ $model->file('gambar')->preview() }}"
                                                    alt="{{ $model->nama_barang }}" />
                                            </div>
                                        </div>
                                    @else
                                        <span class="badge badge-ghost">No Image</span>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($model->harga, 0, ',', '.') }}</td>
                                <td>{{ $model->stok }}</td>
                                <td>{{ $model->keterangan }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data</td>
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