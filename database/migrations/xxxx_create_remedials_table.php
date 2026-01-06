<?php

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

        // Relasi ke tabel mahasiswi
        $table->foreign('id_mahasiswi')->references('id_mahasiswi')->on('mahasiswi')->onDelete('cascade');
    });
}