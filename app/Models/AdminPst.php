<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        // 1. Dapatkan URL dasar. Jika tidak ada foto, gunakan placeholder.
        $url = $this->photo_path 
            ? asset('storage/' . $this->photo_path) 
            : asset('images/admin_photo.jpg');
        
        // 2. TAMBAHAN: Tambahkan parameter versi ('v') dari timestamp updated_at.
        // Ini memaksa browser untuk memuat ulang file karena URL dianggap baru setiap kali diupdate.
        $timestamp = $this->updated_at ? $this->updated_at->timestamp : (time());

        return $url . '?v=' . $timestamp; // <--- BARIS INI YANG HARUS DIUBAH
    }

    /**
     * Override boot() untuk memastikan hanya ada satu admin yang sedang bertugas dengan urutan terkecil.
     */
    protected static function booted()
    {
        // 1. Logic untuk memastikan hanya satu admin yang sedang bertugas
        static::saving(function (AdminPst $adminPst) {
            // Jika statusnya 'Sedang Bertugas'
            if ($adminPst->isDirty('status_jaga') && $adminPst->status_jaga === 'Sedang Bertugas') {
                // Nonaktifkan semua admin lain
                static::where('id', '!=', $adminPst->id)->update(['status_jaga' => 'Tidak Bertugas']);
            }
        });
        
        // 2. Logic untuk menghapus file foto saat Model dihapus (DELETING EVENT)
        static::deleting(function (AdminPst $adminPst) {
            if ($adminPst->photo_path) {
                // Hapus file dari storage sebelum baris database dihapus
                Storage::disk('public')->delete($adminPst->photo_path);
            }
        });
    }
}