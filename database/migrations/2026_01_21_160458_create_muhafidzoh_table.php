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
        Schema::create('muhafidzoh', function (Blueprint $table) {
            $table->bigIncrements('id_muhafidzoh');
            $table->string('nama_muhafidzoh');
            $table->string('keterangan')->nullable();
            $table->unsignedBigInteger('id_kelompok')->nullable()->index('muhafidzoh_id_kelompok_foreign');
            $table->unsignedBigInteger('id_tempat')->nullable()->index('muhafidzoh_id_tempat_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muhafidzoh');
    }
};
