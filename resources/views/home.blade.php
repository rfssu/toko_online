@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 mb-4">
        <img src="images/logo-text.png" class="rounded mx-auto d-block" alt="logotext">
            <form action="{{ route('home') }}" method="get">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search barang" value="{{ request()->get('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
        @foreach($barangs as $barang)
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="{{ asset('uploads/' . $barang->gambar) }}" class="card-img-top" style="height: 400px; width: auto;" alt="...">
                <div class="card-body">
                    <h5 class="card-title"><strong>{{ $barang->nama_barang}}</strong></h5>
                    <p class="card-text">
                        <strong>Harga :</strong> Rp. {{ number_format($barang->harga) }} <br>
                        <strong>Stok :</strong> {{ $barang->stok }} <br>
                        <hr>
                        <strong>Keterangan :</strong> <br>
                        {{ $barang->keterangan }}
                    </p>
                    <a href="{{ url('pesan') }}/{{ $barang->id }}" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Pesan</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    {{ $barangs->links() }}
</div>
@endsection