<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'menu_id', 'quantity', 'price_at_order', 'note',
        'status', 'accepted_at', 'ready_at'
    ];

    protected function casts(): array
    {
        return [
            'price_at_order' => 'decimal:2',
            'accepted_at' => 'datetime',
            'ready_at' => 'datetime',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
    
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->price_at_order;
    }
}
