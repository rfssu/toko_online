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

                        <!-- Import Dropdown -->
                        <div class="dropdown dropdown-end w-full sm:w-auto">
                            <label tabindex="0" class="btn btn-primary w-full sm:w-auto">
                                <i class="ri-upload-line"></i>
                                Import
                                <i class="ri-arrow-down-s-line"></i>
                            </label>
                            <ul tabindex="0"
                                class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-64 mt-2">
                                <li>
                                    <a href="{{ route('barangs.template') }}" class="gap-2">
                                        <i class="ri-file-excel-line text-green-600"></i>
                                        <span>Download Template</span>
                                    </a>
                                </li>
                                <li>
                                    <a onclick="document.getElementById('import_modal').showModal()" class="gap-2">
                                        <i class="ri-upload-cloud-line text-blue-600"></i>
                                        <span>Upload File Import</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
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

    <!-- Import Modal -->
    <dialog id="import_modal" class="modal">
        <div class="modal-box max-w-2xl">
            <h3 class="font-bold text-lg mb-4">
                <i class="ri-upload-cloud-line text-primary"></i>
                Import Barang dari Excel/CSV
            </h3>

            <form action="{{ route('barangs.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Instructions -->
                <div class="alert alert-info mb-4">
                    <i class="ri-information-line"></i>
                    <div>
                        <p class="font-bold">Instruksi:</p>
                        <ol class="list-decimal list-inside text-sm mt-2">
                            <li>Download template Excel terlebih dahulu</li>
                            <li>Isi data sesuai format: nama_barang, harga, stok, keterangan</li>
                            <li>Upload file (.xlsx, .xls, atau .csv)</li>
                            <li><strong>Produk sudah ada?</strong> Stok akan <strong>ditambahkan</strong> + harga diupdate
                            </li>
                            <li><strong>Produk baru?</strong> Akan ditambahkan sebagai produk baru</li>
                            <li>Gambar produk dapat diupload manual setelah import</li>
                        </ol>
                    </div>
                </div>

                <!-- File Upload -->
                <div class="form-control w-full mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Pilih File</span>
                    </label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                        class="file-input file-input-bordered file-input-primary w-full" />
                    <label class="label">
                        <span class="label-text-alt">Format: .xlsx, .xls, .csv (Max: 2MB)</span>
                    </label>
                </div>

                <!-- Sample Format -->
                <div class="overflow-x-auto mb-4">
                    <p class="text-sm font-semibold mb-2">Contoh Format:</p>
                    <table class="table table-xs table-bordered">
                        <thead>
                            <tr class="bg-base-200">
                                <th>nama_barang</th>
                                <th>harga</th>
                                <th>stok</th>
                                <th>keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Bakpia Pathok</td>
                                <td>50000</td>
                                <td>100</td>
                                <td>Rasa Coklat</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="modal-action">
                    <button type="button" onclick="document.getElementById('import_modal').close()" class="btn btn-ghost">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-upload-line"></i>
                        Upload & Import
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
@endsection