<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infographic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'content_url',
        'status',
        'urutan',
    ];

    public static $typeOptions = ['Foto (Upload)', 'Video (URL Embed)'];
    public static $statusOptions = ['Aktif', 'Tidak Aktif'];

    /**
     * Helper: Menentukan apakah konten ini adalah foto.
     */
    public function isPhoto()
    {
        return $this->type === 'Foto (Upload)';
    }

    /**
     * Helper: Mengonversi URL YouTube biasa ke URL embed.
     */
    public static function convertToEmbedUrl($url)
    {
        // Mendukung format: https://www.youtube.com/watch?v=...
        // dan format pendek: https://youtu.be/...
        if (strpos($url, 'youtu.be') !== false) {
            $parts = explode('/', $url);
            $videoId = end($parts);
        } elseif (strpos($url, 'youtube.com') !== false && strpos($url, 'v=') !== false) {
            parse_str(parse_url($url, PHP_URL_QUERY), $vars);
            $videoId = $vars['v'] ?? null;
        } else {
            return $url; // Kembalikan URL asli jika bukan format YouTube yang dikenal
        }

        return $videoId ? "https://www.youtube.com/embed/{$videoId}" : $url;
    }
}