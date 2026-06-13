<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    // ID is string format TRX-YYYYMMDD-XXXX
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'table_id', 'customer_name', 'user_id', 'customer_session_id',
        'source', 'status', 'payment_method', 'cash_amount', 'change_amount', 'payment_time', 'midtrans_order_id'
    ];

    protected function casts(): array
    {
        return [
            'payment_time' => 'datetime',
        ];
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getTotalAttribute()
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->quantity * $item->price_at_order;
        }
        return $total;
    }
}
