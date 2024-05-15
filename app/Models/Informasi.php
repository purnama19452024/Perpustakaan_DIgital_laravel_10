<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informasi extends Model
{
    protected $table = "informasi";
    protected $primaryKey = "id";
    protected $fillable = [
        'namapeprpustakaan',
        'email',
        'nomortlp',
        'alamat',
        'provinsi',
    ];
}
