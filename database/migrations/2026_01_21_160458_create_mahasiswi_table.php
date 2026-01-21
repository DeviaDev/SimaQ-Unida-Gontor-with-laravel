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
            $table->bigIncrements('id_mahasiswi');
            $table->string('nama_mahasiswi');
            $table->string('prodi', 100);
            $table->integer('semester');
            $table->unsignedBigInteger('id_muhafidzoh')->nullable()->index('mahasiswi_id_muhafidzoh_foreign');
            $table->unsignedBigInteger('id_dosen')->nullable()->index('mahasiswi_id_dosen_foreign');
            $table->unsignedBigInteger('id_kelompok')->nullable()->index('mahasiswi_id_kelompok_foreign');
            $table->unsignedBigInteger('id_tempat')->nullable()->index('mahasiswi_id_tempat_foreign');
            $table->timestamps();
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
