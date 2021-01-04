<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['user_id', 'transaction_id', 'payout_id', 'amount', 'currency', 'type', 'status', 'order_id'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
