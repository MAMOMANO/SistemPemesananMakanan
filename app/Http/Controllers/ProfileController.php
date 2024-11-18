<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Mengambil pengguna yang sedang login

        // Jika pengguna tidak ada, alihkan ke halaman login atau tampilkan pesan
        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        return view('profile', compact('user'));
    }
}
