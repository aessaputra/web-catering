<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_item_id',
        'quantity',
        'price',
        'sub_total',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sub_total' => 'decimal:2',
        ];
    }

    /**
     * Get the order that owns the order item.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the menu item that the order item refers to.
     */
    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
