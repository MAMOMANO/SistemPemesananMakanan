<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Menampilkan formulir login
    public function showLoginForm()
    {
        return view('auth.login'); // Pastikan ada view auth/login.blade.php
    }


    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Cek apakah pengguna terdaftar
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'Email ini belum terdaftar. Silakan daftar terlebih dahulu.']);
        }

        // Jika pengguna ada, lanjutkan dengan proses login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('orders.index')); // Ganti dengan rute yang sesuai
        }

        return redirect()->back()->withErrors(['password' => 'Kata sandi salah.']);
    }


    // Proses logout
    public function logout()
    {
        Auth::logout();
         session()->invalidate();
         session()->regenerateToken(); 
        return redirect('/login');
    }

    // Menampilkan formulir pendaftaran
    public function showRegistrationForm()
    {
        return view('auth.register'); // Pastikan ada view auth/register.blade.php
    }

    // Proses registrasi
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'required|string|max:255', // Validasi untuk alamat
        ]);

        if ($validator->fails()) {
            return redirect()->route('register')
                ->withErrors($validator)
                ->withInput();
        }

        // Simpan pengguna baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hashing password
            'address' => $request->address, // Menyimpan alamat
        ]);

        return redirect()->route('register.success')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}
