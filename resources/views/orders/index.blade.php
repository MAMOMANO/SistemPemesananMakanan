@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>Keranjang Pesanan</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Pastikan ini masih di dalam div.container -->
        @if (session('cart') && count(session('cart')) > 0)
            <ul class="list-group">
                @foreach (session('cart') as $food_id => $details)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Nama: {{ $details['name'] }}</h5>
                            <p>Harga: Rp {{ number_format($details['price'], 2, ',', '.') }}</p>
                            <p>Jumlah: {{ $details['quantity'] }}</p>
                            <img src="{{ asset('images/' . $details['image']) }}" alt="{{ $details['name'] }}"
                                style="width: 50px;">
                        </div>

                        <div>
                            <form action="{{ route('cart.increase', $food_id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm">Tambah</button>
                            </form>

                            <form action="{{ route('cart.decrease', $food_id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning btn-sm">Kurangi</button>
                            </form>

                            <form action="{{ route('cart.remove', $food_id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
            <a href="{{ route('order.checkout') }}" class="btn btn-primary mt-3">Checkout</a>
        @else
            {{-- <p>Belum ada pesanan di keranjang.</p> --}}
            <a href="{{ route('payments.index', ['orderId' => $order->id]) }}" class="btn btn-primary mt-3">Lanjut ke
                Pembayaran</a>
        @endif

    </div>
@endsection
