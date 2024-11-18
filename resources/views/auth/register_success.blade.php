@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Pendaftaran Berhasil!</h1>
    <p>Anda telah berhasil mendaftar. Silakan login untuk melanjutkan.</p>
    <a href="{{ route('login') }}" class="btn btn-primary">Back to Login</a>
</div>
@endsection