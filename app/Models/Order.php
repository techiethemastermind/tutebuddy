<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\User;

class Order extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function refunded()
    {
        $r = Refund::where('order_id', $this->id)->first();
        return !empty($r) ? true : false;
    }
}
