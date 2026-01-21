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
        Schema::create('dosen', function (Blueprint $table) {
            $table->bigIncrements('id_dosen');
            $table->string('nama_dosen');
            $table->unsignedBigInteger('id_kelompok')->nullable()->index('dosen_id_kelompok_foreign');
            $table->unsignedBigInteger('id_muhafidzoh')->nullable()->index('dosen_id_muhafidzoh_foreign');
            $table->unsignedBigInteger('id_tempat')->nullable()->index('dosen_id_tempat_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
