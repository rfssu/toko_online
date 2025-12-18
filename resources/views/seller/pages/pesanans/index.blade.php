@extends('seller/layouts/main')

@section('breadcrumb')
    <a href="{{ route('pesanans.index') }}" class="flex items-center gap-2">
        Pesanan
    </a>
@endsection
@php
    $indexRoute = fn() => route('pesanans.index');
    $editRoute = fn($model) => route('pesanans.edit', $model->id);

    use App\Models\Pesanan;
@endphp
@section('pages')
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h1 class="card-title text-lg "><b>Daftar Pesanan</b></h1>
                <a href="{{ route('pesanans.scanner') }}" class="btn btn-primary">
                    <i class="ri-qr-scan-2-line"></i>
                    Scan QR Code
                </a>
            </div>

            <form method="GET" action="{{ $indexRoute() }}" class="mb-4">
                <div class="flex flex-col sm:flex-row gap-2 w-full justify-end">
                    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                        {{ $models->links('seller/components/per-page') }}
                        <div class="form-control">
                            <select name="status" class="select select-bordered w-full" onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                @foreach (Pesanan::STATUS as $key => $label)
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
                            <x-sort-th column="pesanans.kode" label="Kode" />
                            <x-sort-th column="users.name" label="Nama Pembeli" />
                            <x-sort-th column="pesanans.status" label="Status" />
                            <x-sort-th column="users.no_hp" label="No HP" />
                            <th>PIC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($models as $index => $model)
                            <tr>
                                <td>{{ $models->firstItem() + $index }}</td>
                                <td>
                                    @if ($model->status === 'co')
                                        <a href="{{ $editRoute($model) }}" onclick="modalFormAjax(this, event)"
                                            data-modal-size="large" class="btn btn-success btn-sm text-white">
                                            <i class="ri-check-line"></i>
                                        </a>
                                    @elseif ($model->status === 'pickup')
                                        <a href="{{ $editRoute($model) }}" onclick="modalFormAjax(this, event)"
                                            data-modal-size="large" class="btn btn-warning btn-sm text-white">
                                            <i class="ri-article-line"></i>
                                        </a>
                                    @endif
                                </td>
                                <td>{{ $model->kode }}</td>
                                <td>{{ $model->user->name }}</td>
                                <td>{{ $model->status_val }}</td>
                                <td>{{ $model->user->no_hp }}</td>
                                <td>{{ $model->toPic->name ?? '-' }}</td>
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