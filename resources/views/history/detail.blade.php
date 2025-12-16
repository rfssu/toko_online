@extends('buyer.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-1">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h3><strong>Sukses Checkout</strong></h3>
                            <h5>Pesanan sukses check out, lanjut untuk pembayaran silahkan transfer ke rekening <br>
                                Bank A Nomer Rekening : <strong>1234-5678-9876</strong> dengan nominal : <strong> Rp. {{ number_format($pesanan->jumlah_harga) }}</strong></h5>
                        </div>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="card-body">
                        <h3><i class="fa fa-shopping-cart"></i> Detail Pemesanan</h3>
                        @if (!empty($pesanan))
                            <p align="right">Tanggal Pesan : {{ $pesanan->tanggal }}</p>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Gambar</th>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    @foreach ($pesanan_details as $pesanan_detail)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <img src="{{ url('uploads') }}/{{ $pesanan_detail->barang->gambar }}" width="100" alt="...">
                                            </td>
                                            <td>{{ $pesanan_detail->barang->nama_barang }}</td>
                                            <td>{{ $pesanan_detail->jumlah }} pcs</td>
                                            <td>Rp. {{ number_format($pesanan_detail->barang->harga) }}</td>
                                            <td align="right">Rp. {{ number_format($pesanan_detail->total) }}</td>

                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="5" align="right"><strong>Total Harga :</strong></td>
                                        <td align="right"><strong>Rp. {{ number_format($pesanan->total) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" align="right"><strong>Kode Pesananan :</strong></td>
                                        <td align="right"><strong>{{ $pesanan->kode }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" align="right"><strong>Total yang harus ditransfer :</strong></td>
                                        <td align="right"><strong>Rp. {{ number_format($pesanan->total) }}</strong></td>
                                    </tr>

                                </tbody>
                            </table>
                        @else
                            <p>Keranjang Kosong</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
