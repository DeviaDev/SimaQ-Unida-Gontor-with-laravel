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
        Schema::create('kelompok_lt', function (Blueprint $table) {
            $table->id('id_kelompok');
            $table->string('kode_kelompok',50)->nullable();
            $table->unsignedBigInteger('id_tempat')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('kelompok_lt');
    }
};
