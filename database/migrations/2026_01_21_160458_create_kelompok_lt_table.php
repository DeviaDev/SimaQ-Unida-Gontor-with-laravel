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
            $table->bigIncrements('id_kelompok');
            $table->string('kode_kelompok', 50)->nullable();
            $table->unsignedBigInteger('id_tempat')->nullable()->index('kelompok_lt_id_tempat_foreign');
            $table->timestamps();
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
