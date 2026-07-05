<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Mengaitkan transaksi dengan customer pemiliknya
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}