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
        Schema::table('mahasiswi', function (Blueprint $table) {
            $table->foreign(['id_dosen'])->references(['id_dosen'])->on('dosen')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['id_kelompok'])->references(['id_kelompok'])->on('kelompok_lt')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['id_muhafidzoh'])->references(['id_muhafidzoh'])->on('muhafidzoh')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['id_tempat'])->references(['id_tempat'])->on('tempat')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswi', function (Blueprint $table) {
            $table->dropForeign('mahasiswi_id_dosen_foreign');
            $table->dropForeign('mahasiswi_id_kelompok_foreign');
            $table->dropForeign('mahasiswi_id_muhafidzoh_foreign');
            $table->dropForeign('mahasiswi_id_tempat_foreign');
        });
    }
};
