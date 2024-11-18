<?php

namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Food; // Tambahkan ini untuk mengimpor model Food
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        // Mengambil keranjang dari session
        $cart = session()->get('cart', []);

        // Mengambil orders yang belum dibayar untuk pengguna yang sedang login
        $orders = Order::where('user_id', Auth::id())->where('status', 'belum_bayar')->get();

        return view('orders.index', compact('cart', 'orders'));
    }

    public function add(Request $request, $id)
    {
        if (!Auth::check()) { // Mengubah logika menjadi pengecekan apakah user tidak login
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu untuk menambahkan ke keranjang.');
        }

        $food = Food::findOrFail($id);
        $cart = session()->get('cart', []);

        // Log keranjang sebelum menambah makanan
        info('Cart before adding: ', $cart);

        if (isset($cart[$food->id])) {
            $cart[$food->id]['quantity']++;
        } else {
            $cart[$food->id] = [
                'name' => $food->name,
                'quantity' => 1,
                'price' => $food->price,
                'image' => $food->image,
            ];
        }

        session()->put('cart', $cart);

        // Log keranjang setelah menambah makanan
        info('Cart session data: ', session()->get('cart'));

        return redirect()->route('menus.index')->with('success', 'Makanan berhasil ditambahkan ke keranjang!');
    }

    public function increaseQuantity($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
            session()->put('cart', $cart);
        }   

        return redirect()->route('orders.index')->with('success', 'Jumlah item berhasil ditambah.');
    }

    // Fungsi untuk mengurangi jumlah item di keranjang
    public function decreaseQuantity($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
            } else {
                unset($cart[$id]); // Menghapus item jika quantity = 0
            }
            session()->put('cart', $cart);
        }

        return redirect()->route('orders.index')->with('success', 'Jumlah item berhasil dikurangi.');
    }

    // Fungsi untuk menghapus item dari keranjang
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('orders.index')->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
