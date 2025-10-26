<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class QueueStatus extends Model
{
    use HasFactory;
    
    // Nama tabel kita adalah queue_statuses
    protected $fillable = ['current_number']; 
    // Matikan timestamps karena tabel ini sangat sederhana
    public $timestamps = false; 

    // Helper untuk mendapatkan status antrian (record ID 1)
    public static function getCurrent()
    {
        // Temukan record ID 1, jika tidak ada, buat baru dengan nomor 1
        return static::firstOrCreate(['id' => 1], ['current_number' => 1]);
    }
}
