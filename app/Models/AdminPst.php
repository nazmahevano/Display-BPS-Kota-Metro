<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPst extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'jabatan',
        'photo_path',
        'status_jaga',
        'urutan',
    ];

    // Status yang tersedia
    public static $statusOptions = ['Sedang Bertugas', 'Tidak Bertugas'];

    /**
     * Helper untuk mendapatkan URL foto
     */
    public function getPhotoUrlAttribute()
    {
        return $this->photo_path ? asset('storage/' . $this->photo_path) : asset('images/admin_photo.jpg');
    }

    /**
     * Override boot() untuk memastikan hanya ada satu admin yang sedang bertugas dengan urutan terkecil.
     */
    protected static function booted()
    {
        static::saving(function (AdminPst $adminPst) {
            // Jika statusnya 'Sedang Bertugas'
            if ($adminPst->isDirty('status_jaga') && $adminPst->status_jaga === 'Sedang Bertugas') {
                // Nonaktifkan semua admin lain
                static::where('id', '!=', $adminPst->id)->update(['status_jaga' => 'Tidak Bertugas']);
            }
        });
    }
}