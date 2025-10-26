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
       Schema::create('queue_statuses', function (Blueprint $table) {
            $table->id();
            // Kita hanya perlu satu record, jadi ID 1 akan selalu menyimpan nomor antrian saat ini
            $table->integer('current_number')->default(1); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue_statuses');
    }
};
