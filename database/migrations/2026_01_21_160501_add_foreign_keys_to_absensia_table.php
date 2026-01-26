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
        Schema::table('absensia', function (Blueprint $table) {
            $table->foreign(['id_muhafidzoh'], 'fk_absensi_muhafidzoh')->references(['id_muhafidzoh'])->on('muhafidzoh')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensia', function (Blueprint $table) {
            $table->dropForeign('fk_absensi_muhafidzoh');
        });
    }
};
