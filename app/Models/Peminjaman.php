<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    const STATUS_DI_PINJAM = '1';
    const STATUS_DI_KEMBALIKAN = '0';
    protected $table = "peminjaman";
    protected $primaryKey = "id";
    protected $fillable = [
        'user_id',
        'buku_id',
        'tanggalpeminjaman',
        'tanggalpengembalian',
        'statuspeminjaman',
    ];

    public static function booted()
    {
        static::creating(function ($q) {
            if (!isset ($q->statuspeminjaman))
                $q->statuspeminjaman = self::STATUS_DI_PINJAM;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id', 'id');
    }

}
