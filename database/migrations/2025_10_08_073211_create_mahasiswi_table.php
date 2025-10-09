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
        Schema::create('mahasiswi', function (Blueprint $table) {
            $table->id('id_mahasiswi');
        $table->string('nama_mahasiswi', 255);
        $table->string('prodi', 100);
        $table->integer('semester');
        $table->unsignedBigInteger('id_muhafidzoh')->nullable();
        $table->unsignedBigInteger('id_dosen')->nullable();
        $table->unsignedBigInteger('id_kelompok')->nullable();
        $table->unsignedBigInteger('id_tempat')->nullable();
        $table->timestamps();

        $table->foreign('id_muhafidzoh')
              ->references('id_muhafidzoh')
              ->on('muhafidzoh')
              ->onDelete('set null');

        $table->foreign('id_dosen')
              ->references('id_dosen')
              ->on('dosen')
              ->onDelete('set null');

        $table->foreign('id_kelompok')
              ->references('id_kelompok')
              ->on('kelompok_lt')
              ->onDelete('set null');

        $table->foreign('id_tempat')
              ->references('id_tempat')
              ->on('tempat')
              ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswi');
    }
};
