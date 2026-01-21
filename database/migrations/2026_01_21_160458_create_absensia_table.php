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
        Schema::create('absensia', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_muhafidzoh')->index('idx_id_muhafidzoh');
            $table->integer('pertemuan')->default(0);
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'izin', 'alpha']);
            $table->string('gedung', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensia');
    }
};
