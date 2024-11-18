<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <!-- Navbar -->
    <!-- <nav>
        <ul>
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('menus.index') }}">Menu</a></li>
            <li><a href="{{ route('orders.index') }}">Orders</a></li>
            <li><a href="{{ route('profile') }}">Profile</a></li>
        </ul>
    </nav> -->

    <!-- Header Section -->
    <!-- <header>
        <h1>Profil Pengguna</h1>
    </header> -->

    <!-- Main Content Section -->
    @extends('layouts.app')
    @section('content')
    <div class="container">
        <h2>Informasi Pengguna</h2>
        @if ($user)
        <p><strong>Nama:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Tanggal Bergabung:</strong> {{ $user->created_at->format('d M Y') }}</p>
        @else
        <p>User not found or not authenticated.</p>
        @endif
    </div>
    @endsection
    <!-- Footer
    <footer>
        <p>&copy; 2024 Aplikasi Pemesanan Makanan. All rights reserved.</p>
    </footer> -->
</body>

</html>