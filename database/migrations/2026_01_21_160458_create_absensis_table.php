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
        Schema::create('absensis', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedBigInteger('id_mahasiswi')->index('idx_id_mahasiswi');
            $table->tinyInteger('pertemuan');
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'izin', 'alpha'])->nullable();
            $table->string('prodi', 50)->nullable();
            $table->integer('semester')->nullable();
            $table->string('kelompok', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
