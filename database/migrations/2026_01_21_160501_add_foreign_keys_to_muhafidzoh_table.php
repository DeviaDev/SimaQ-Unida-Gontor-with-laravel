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
        Schema::table('muhafidzoh', function (Blueprint $table) {
            $table->foreign(['id_kelompok'])->references(['id_kelompok'])->on('kelompok_lt')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['id_tempat'])->references(['id_tempat'])->on('tempat')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('muhafidzoh', function (Blueprint $table) {
            $table->dropForeign('muhafidzoh_id_kelompok_foreign');
            $table->dropForeign('muhafidzoh_id_tempat_foreign');
        });
    }
};
