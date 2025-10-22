<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'payment_method',
        'postal_order',
        'address_order',
        'building_order'
    ];

    protected $casts = [
        'payment_method' => 'integer',
    ];

    public const  PAYMENT_METHOD = [
        0 => 'コンビニ払い',
        1 => 'カード支払い'
    ];

    public function getPaymentMethodLabelAttribute()
    {
        return self::PAYMENT_METHOD[$this->payment_method] ?? '不明';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

}
