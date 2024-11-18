@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar Pembayaran</h2>

    <table class="table">
        <thead>
            <tr>
                <th>ID Pembayaran</th>
                <th>Jumlah Pesanan</th>
                {{-- <th>Total Pembayaran</th> --}}
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @if($payments->isEmpty())
            <tr>
                <td colspan="3">Tidak ada pembayaran terkait.</td>
            </tr>
            @else
            @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->id }}</td>
                <td>{{ $payment->amount }}</td>
                <td>{{ $payment->status }}</td>
                {{-- <td>{{ $payment->total_pembayaran }}</td> --}}
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>

    <form action="{{ route('payment.store') }}" method="POST">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <input type="number" name="amount" placeholder="Jumlah Pembayaran" required>
        <button type="submit" class="btn btn-success">Konfirmasi Pembayaran</button>
    </form>
</div>
@endsection