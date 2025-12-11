@extends('seller/layouts/main')
@section('breadcrumb')
    <a href="{{ route('pesanans.index') }}" class="flex items-center gap-2">
        Kelola Pesanan
    </a>
@endsection
@section('pages')
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h1 class="card-title text-lg mb-4"><b>Daftar Pesanan Pickup</b></h1>
            @if(session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error mb-4">{{ session('error') }}</div>
            @endif
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Customer</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesanans as $pesanan)
                            <tr>
                                <td><b>#{{ $pesanan->kode }}</b></td>
                                <td>
                                    {{ $pesanan->user->name }}<br>
                                    <small class="text-gray-500">{{ $pesanan->user->no_hp }}</small>
                                </td>
                                <td>{{ $pesanan->tanggal }}</td>
                                <td>Rp {{ number_format($pesanan->jumlah_harga, 0, ',', '.') }}</td>
                                <td>
                                    @if($pesanan->status == 'checkout')
                                        <span class="badge badge-warning">Menunggu Pickup</span>
                                    @elseif($pesanan->status == 'siap_pickup')
                                        <span class="badge badge-success">Siap Diambil</span>
                                    @endif
                                </td>
                                <td class="flex gap-2">
                                    <a href="{{ route('pesanans.show', $pesanan->id) }}" class="btn btn-sm btn-ghost"
                                        title="Lihat Detail">
                                        <i class="ri-eye-line"></i>
                                    </a>

                                    @if($pesanan->status == 'checkout')
                                        <form action="{{ route('pesanans.ready', $pesanan->id) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Tandai pesanan ini siap pickup?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="ri-check-line"></i> Siap Pickup
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-500">
                                    Tidak ada pesanan saat ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $pesanans->links('seller/components/paginate') }}
            </div>
        </div>
    </div>
@endsection