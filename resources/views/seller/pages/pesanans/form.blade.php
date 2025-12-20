@php
    $formHref = fn($model) => route('pesanans.update', $model->id);
    // dd($model);

    $statusCo = $model->status === 'co';
    $statusPickup = $model->status === 'pickup';
@endphp

<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">{{ $statusCo ? 'Konfirmasi Pesanan' : 'Detail Pesanan' }}</h2>

    {{-- Ringkasan Pesanan --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="card bg-base-200 shadow">
            <div class="card-body p-4">
                <div class="flex items-center gap-2 text-sm text-base-content/60 mb-1">
                    <i class="ri-file-list-line"></i>
                    <span>Kode Pesanan</span>
                </div>
                <p class="text-xl font-bold">#{{ $model->kode }}</p>
                <p class="text-xs text-base-content/50">{{ $model->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="card bg-base-200 shadow">
            <div class="card-body p-4">
                <div class="flex items-center gap-2 text-sm text-base-content/60 mb-1">
                    <i class="ri-user-line"></i>
                    <span>Pelanggan</span>
                </div>
                <p class="text-xl font-bold">{{ $model->user->name }}</p>
                <p class="text-xs text-base-content/50">{{ $model->user->no_hp }}</p>
            </div>
        </div>

        <div class="card bg-base-200 shadow">
            <div class="card-body p-4">
                <div class="flex items-center gap-2 text-sm text-base-content/60 mb-1">
                    <i class="ri-calendar-line"></i>
                    <span>Tanggal</span>
                </div>
                <p class="text-xl font-bold">
                    {{ $statusPickup ? $model->tanggal_pickup->format('d M Y') : $model->created_at->format('d M Y') }}
                </p>
                <div class="badge badge-warning badge-sm mt-1">{{ $model->status_val }}</div>
            </div>
        </div>
    </div>

    {{-- Tabel Detail Pesanan --}}
    <div class="card bg-base-200 shadow mb-6">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Barang</th>
                            <th class="text-right">Jumlah</th>
                            <th class="text-right">Harga</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($model->pesanan_detail as $pesanan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $pesanan->barang->nama_barang }}</td>
                                <td class="text-right">{{ $pesanan->jumlah ?? 0 }} </td>
                                <td class="text-right">@currency($pesanan->harga ?? 0)</td>
                                <td class="text-right">@currency($pesanan->total ?? 0)</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="font-bold">
                            <td colspan="4" class="text-right">Total</td>
                            <td class="text-right text-lg">@currency($model->total ?? 0)</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Aksi --}}
    @if ($statusCo)
        <form action="{{ $formHref($model) }}" method="POST" id="form-elem" class="flex justify-end">
            @csrf
            @method('PUT')
            <button class="btn btn-primary" type="submit">
                <i class="ri-check-line"></i>
                Konfirmasi Pickup
            </button>
        </form>
    @endif
</div>

<script id="data-json" type="application/json">
    {!! $model->toJson(JSON_FORCE_OBJECT) !!}
</script>

<script type="module">
    jsonScriptToFormFields('#form-elem', '#data-json');
    $('#form-elem').formAjaxSubmit();
</script>
