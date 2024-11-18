<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function store(Request $request)
    {

        // Lakukan validasi terlebih dahulu
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric',
        ]);

        
        // Setelah validasi, buat pembayaran baru
        $payment = new Payment();
        $payment->user_id = Auth::id(); // Simpan user_id pengguna yang login
        $payment->order_id = $request->order_id;
        $payment->amount = $request->amount;
        $payment->payment_method = 'debit';
        $payment->status = 'completed'; // Set status menjadi "completed"
        $payment->save();

        return redirect()->route('payments.index', $request->order_id)->with('success', 'Pembayaran berhasil dilakukan.');
    }

    public function index($orderId)
    {
        // {
        $order = Order::find($orderId);    // Ambil data order berdasarkan ID

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Order tidak ditemukan.');
        }

        $payments = Payment::where('order_id', $orderId)->where('user_id', Auth::id())->get();
        return view('payments.index', compact('order', 'payments'));
    }
}
