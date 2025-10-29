<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'institution',
        'phone',
        'purpose',
        'objective',
    ];
    
    // Keperluan yang tersedia untuk filter/dropdown
    public static $purposes = [
        'PST',
        'TATA USAHA',
        'IPDS',
        'SOSIAL',
        'TEKNIS',
        'LAINNYA',
    ];
}