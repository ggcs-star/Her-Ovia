<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = [
    'razorpay_order_id',
    'order_number',
    'user_id',
    'shipping_address_id',
    'billing_address_id',
    'subtotal',
    'tax',
    'shipping',
    'platform_fee',
    'discount',
    'total',
    'coupon_code',
    'payment_method',
    'status',
    'payment_status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {

            $order->order_number =
            'ORD-'.date('Ymd').'-'.mt_rand(1000,9999);

        });

        }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class,'order_id','id');
    }

    public function shippingAddress()
    {
    return $this->belongsTo(UserAddress::class,'shipping_address_id');
    }

    public function billingAddress()
    {
        return $this->belongsTo(UserAddress::class,'billing_address_id');
    }

}