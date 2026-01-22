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
        Schema::create('remedial', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_mahasiswi');
            $table->string('prodi')->nullable();
            $table->string('semester', 50)->nullable();
            $table->string('kategori', 100)->nullable();
            $table->string('materi');
            $table->string('nilai', 10);
            $table->string('nilai_remedial', 5)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remedial');
    }
};
