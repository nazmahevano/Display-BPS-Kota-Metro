<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('infographics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            // Tipe: Foto (Upload) atau Video (URL Embed)
            $table->enum('type', ['Foto (Upload)', 'Video (URL Embed)']); 
            // Path file foto atau URL embed video
            $table->text('content_url'); 
            // Status: Aktif atau Tidak Aktif
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif'); 
            $table->integer('urutan')->default(0); // Urutan tampil di slideshow
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infographics');
    }
};
