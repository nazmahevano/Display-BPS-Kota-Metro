<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RunningText extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'content',
        'status',
        'urutan',
    ];

    public static $statusOptions = ['Aktif', 'Tidak Aktif'];
}