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
            $table->id('id_muhafidzoh');
            $table->string('nama_muhafidzoh',255);
            $table->string('keterangan',255)->nullable();
            $table->unsignedBigInteger('id_kelompok')->nullable();
            $table->unsignedBigInteger('id_tempat')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('muhafidzoh');
    }
};
