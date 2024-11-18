<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'menu_id',
        'quantity',
        'price',
    ];

    /**
     * Relasi dengan model Order.
     * Setiap OrderItem terkait dengan satu Order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Relasi dengan model Menu.
     * Setiap OrderItem terkait dengan satu Menu.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    /**
     * Mendapatkan subtotal dari OrderItem.
     * Mengalikan quantity dengan price untuk mendapatkan total harga item.
     *
     * @return float
     */
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->price;
    }

    /**
     * Mendapatkan total harga semua item dalam satu order.
     * Fungsi ini dapat digunakan di tempat lain untuk menghitung total harga pesanan.
     *
     * @param int $orderId
     * @return float
     */
    public static function getTotalOrderPrice($orderId)
    {
        $order = Order::find($orderId);

        if ($order) {
            // Mengambil semua item terkait dan menghitung total dari subtotal masing-masing
            return $order->items->sum(function ($item) {
                return $item->subtotal;
            });
        }

        return 0;
    }
}
