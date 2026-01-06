<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remedials', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_mahasiswi');
            $table->string('prodi')->nullable();
            $table->string('semester')->nullable();
            $table->string('kategori')->nullable();
            $table->string('materi');
            $table->string('nilai'); // Nilai asli C/C-
            $table->timestamps();

            $table->foreign('id_mahasiswi')
                ->references('id_mahasiswi')
                ->on('mahasiswi')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remedials');
    }
};