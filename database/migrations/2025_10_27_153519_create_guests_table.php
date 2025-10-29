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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('institution')->nullable(); // Asal Instansi
            $table->string('phone')->nullable(); // No. Telepon
            $table->string('purpose'); // Keperluan: PST, TATA USAHA, IPDS, SOSIAL, TEKNIS, LAINNYA
            $table->text('objective')->nullable(); // Tujuan
            $table->timestamps(); // created_at akan menjadi kolom Tanggal
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
