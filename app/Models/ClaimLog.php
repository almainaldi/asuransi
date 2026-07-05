<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimLog extends Model
{
    use HasFactory;

    protected $fillable = ['claim_id', 'user_id', 'from_status', 'to_status', 'note'];

    // Relasi: Log ini mencatat user siapa yang melakukan aksi
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}