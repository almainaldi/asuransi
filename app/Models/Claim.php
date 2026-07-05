<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'alamat',
        'no_telepon',
        'email_kontak',
        'tempat_lahir',
        'tanggal_lahir',
        'perencanaan_hidup',
        'nominal_asuransi',
        'tinggi_badan',
        'berat_badan',
        'masalah_kesehatan',
        'total_asuransi_jiwa',
        'status'
    ];

    // Relasi: Klaim ini dimiliki oleh siapa (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Klaim ini punya banyak riwayat log
    public function logs()
    {
        return $this->hasMany(ClaimLog::class);
    }
}
