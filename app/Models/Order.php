<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_qty',
        'total_amount',
        'wallet_used',
        'payment_method',
        'payment_status',
        'status',
        'note',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_phone',
        'shipping_address1',
        'shipping_address2',
        'tracking_url',
    ];

    // 🔥 Relation: Order → Items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // 🔥 Relation: Order → User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}