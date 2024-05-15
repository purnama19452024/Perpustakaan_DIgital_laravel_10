<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity; // Import model Activity

class Buku extends Model
{
    use LogsActivity;

    const STATUS_AKTIVE = '1';
    const STATUS_NON_AKTIVE = '0';

    protected static $recordEvents = ['created', 'updated', 'deleted']; // Record event "created", "updated", dan "deleted"

    protected $table = "buku";
    protected $primaryKey = "id";
    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahunterbit',
        'kategori_id',
        'bukuuplode',
        'avatar',
        'status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['judul', 'penulis', 'penerbit', 'tahunterbit', 'kategori_id', 'bukuuplode', 'avatar', 'status'])
            ->setDescriptionForEvent(function (string $eventName) {
                if ($eventName === 'created') {
                    return 'Buku dibuat';
                } elseif ($eventName === 'updated') {
                    return 'Buku diperbarui';
                } elseif ($eventName === 'deleted') {
                    return 'Buku dihapus';
                }
            });
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('active', function ($builder) {
            $builder->active();
        });

        static::creating(function ($buku) {
            if (!isset($buku->status)) {
                $buku->status = self::STATUS_AKTIVE;
            }
        });

        static::deleting(function ($buku) {
            // Periksa apakah catatan aktivitas yang berkaitan dengan buku sudah ada
            $existingActivity = Activity::where('subject_type', get_class($buku))
                ->where('subject_id', $buku->id)
                ->first();

            // Jika tidak ada catatan aktivitas yang ada, maka buat log aktivitas dan hapus catatan aktivitas yang berkaitan
            if (!$existingActivity) {
                activity()
                    ->performedOn($buku)
                    ->causedBy(auth()->user())
                    ->log('Buku dihapus: ' . $buku->judul);
            }

            // Hapus catatan aktivitas yang berkaitan dengan buku yang dihapus
            Activity::where('subject_type', get_class($buku))
                ->where('subject_id', $buku->id)
                ->delete();
        });


        static::updating(function ($buku) {
            // Check if any attributes have changed
            if ($buku->isDirty()) {
                activity()
                    ->performedOn($buku)
                    ->causedBy(auth()->user())
                    ->log('Buku diperbarui: ' . $buku->judul);
            }
        });
    }

    public function scopeActive($builder)
    {
        return $builder->where('status', self::STATUS_AKTIVE);
    }

    public function category()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
