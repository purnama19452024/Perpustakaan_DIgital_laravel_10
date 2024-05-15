<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = "kategori";
    protected $primaryKey = "id";
    protected $fillable = [
        'namakategori',
    ];
    // Sesuaikan dengan nama tabel kategori Anda

    public function buku()
    {
        return $this->hasMany(Buku::class, 'kategori_id');
    }

    public function comments()
    {
        return $this->hasMany(Buku::class);
    }
}
