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
        Schema::table('absensi_pengurus', function (Blueprint $table) {
            $table->foreign(['id_kegiatan'], 'fk_absensi_kegiatan')->references(['id'])->on('laporan_kegiatan')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['id_pengurus'], 'fk_absensi_pengurus')->references(['id'])->on('pengurus')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi_pengurus', function (Blueprint $table) {
            $table->dropForeign('fk_absensi_kegiatan');
            $table->dropForeign('fk_absensi_pengurus');
        });
    }
};
