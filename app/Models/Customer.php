<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // 1. Relasi ke transaksi
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'customer_id');
    }

    protected static function booted()
    {
        static::deleting(function ($customer) {
            $customer->transactions()->delete();
        });
    }
}