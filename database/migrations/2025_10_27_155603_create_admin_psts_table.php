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
        Schema::create('admin_psts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('jabatan')->nullable();
            $table->string('photo_path')->nullable(); // Path foto di storage
            // Status Jaga: "Sedang Bertugas" atau "Tidak Bertugas"
            $table->enum('status_jaga', ['Sedang Bertugas', 'Tidak Bertugas'])->default('Tidak Bertugas'); 
            $table->integer('urutan')->default(0); // Urutan tampil di display
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_psts');
    }
};
