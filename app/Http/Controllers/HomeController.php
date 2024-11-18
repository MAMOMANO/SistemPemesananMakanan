<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil semua data makanan dari tabel 'foods'
        $foods = Food::all();
        
        // Mengirim data 'foods' ke view
        return view('home', compact('foods'));
    }
}

