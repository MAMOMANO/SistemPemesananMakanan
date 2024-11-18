<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Food;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'food_id' => 'required|exists:foods,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Mengambil data makanan berdasarkan ID
        $food = Food::findOrFail($request->food_id);

        // Mengambil keranjang dari session atau membuat keranjang baru jika kosong
        $cart = session()->get('cart', []);

        // Cek apakah makanan sudah ada di keranjang
        if (isset($cart[$food->id])) {
            // Tambahkan quantity jika makanan sudah ada
            $cart[$food->id]['quantity'] += $request->quantity;
        } else {
            // Tambahkan makanan baru ke keranjang jika belum ada
            $cart[$food->id] = [
                'name' => $food->name,
                'quantity' => $request->quantity,
                'price' => $food->price,
                'image' => $food->image,
            ];
        }

        // Simpan keranjang ke session
        session()->put('cart', $cart);

        // Redirect ke halaman menu dengan pesan sukses
        return redirect()->route('menus.index')->with('success', 'Makanan berhasil ditambahkan ke keranjang.');
    }
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('orders.index')->with('error', 'Keranjang Anda kosong.');
        }

        // Hitung total harga
        $totalPrice = 0;
        foreach ($cart as $food_id => $details) {
            $totalPrice += $details['price'] * $details['quantity'];
        }

        // Buat order baru
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $totalPrice,
            'status' => 'belum_bayar',
        ]);

        // Tambahkan setiap item dalam keranjang ke tabel order_items
        foreach ($cart as $food_id => $details) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => 1,
                'quantity' => $details['quantity'],
                'price' => $details['price'],
            ]);
        }

        // Hapus data keranjang dari session
        session()->forget('cart');

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat. Silakan lanjutkan ke pembayaran.');
    }

    /**
     * Menampilkan halaman keranjang dan pesanan.
     */
    public function index()
    {
        // Ambil data keranjang dari session
        $cart = session()->get('cart', []);
        // Ambil data orders dari database untuk user yang sedang login
        $order = Order::where('user_id', Auth::id())->where('status', 'belum_bayar')->first();

        // Kirim data orders dan cart ke view
        return view('orders.index', compact('cart', 'order'));
    }



    /**
     * Membatalkan pesanan.
     */
    public function cancel(Order $order)
    {
        // Pastikan hanya pesanan milik user yang sedang login yang bisa dibatalkan
        if ($order->user_id == Auth::id()) {
            $order->status = 'cancelled';
            $order->save();
            return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibatalkan.');
        }

        return redirect()->route('orders.index')->with('error', 'Anda tidak diizinkan untuk membatalkan pesanan ini.');
    }
}
