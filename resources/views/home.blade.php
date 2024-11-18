@extends('layouts.app')

@section('title', 'Home - Aplikasi Pemesanan Makanan')

@section('content')
<!-- Header Section -->
<header class="text-center bg-dark text-white py-5">
    <h1>Selamat Datang di Aplikasi Pemesanan Makanan</h1>
    <p>Pilih makanan favoritmu dan pesan sekarang juga!</p>
    <a href="{{ route('menus.index') }}" class="btn btn-primary">Lihat Menu</a>
</header>

<!-- Main Content Section -->
<section class="container my-5 text-center">
    <h2>Promo Menarik!</h2>
    <p>Dapatkan diskon 20% untuk pemesanan pertama Anda!</p>
</section>

<!-- Rekomendasi Makanan -->
<section class="container my-5 text-center">
    <h3>Rekomendasi Makanan</h3>
    <div class="row">
        @foreach($foods as $food)
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <img src="{{ asset('images/' . $food->image) }}" class="card-img-top" alt="{{ $food->name }}" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">{{ $food->name }}</h5>
                    <p class="card-text">Harga: Rp {{ number_format($food->price, 0, ',', '.') }}</p>
                    <form action="{{ route('cart.add', $food->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-success w-100">Tambah ke Keranjang</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection