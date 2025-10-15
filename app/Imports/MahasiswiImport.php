<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\Tempat;
use App\Models\Mahasiswi;
use App\Models\KelompokLT;
use App\Models\Muhafidzoh;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswiImport implements ToModel, WithHeadingRow
{
    /**
     * Header Excel berada di baris ke-2
     */
    public function headingRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        // Normalisasi header biar bisa baca kolom "Nama Mahasiswi", "Nama Dosen", dst.
        $normalized = [];
        foreach ($row as $key => $value) {
            $key = strtolower(str_replace([' ', '-', '.'], '_', trim($key)));
            $normalized[$key] = $value;
        }

        // Abaikan baris yang tidak memiliki nama mahasiswi
        if (empty($normalized['nama_mahasiswi'])) {
            return null;
        }

        // Ambil data relasi berdasarkan nama
        $kelompok = KelompokLT::where('kode_kelompok', $normalized['kelompok'] ?? null)->first();
        $tempat = Tempat::where('nama_tempat', $normalized['tempat'] ?? null)->first();
        $muhafidzoh = Muhafidzoh::where('nama_muhafidzoh', $normalized['nama_muhafidzoh'] ?? null)->first();
        $dosen = Dosen::where('nama_dosen', $normalized['dosen_pembimbing'] ?? $normalized['nama_dosen'] ?? null)->first();

        // Simpan data mahasiswi baru
        return new Mahasiswi([
    'nama_mahasiswi' => $normalized['nama_mahasiswi'] ?? null,
    'prodi'          => $normalized['program_studi'] ?? '-', // default kalau kosong
    'semester'       => $normalized['semester'] ?? 1, // default semester 1
    'id_muhafidzoh'  => $muhafidzoh->id_muhafidzoh ?? null,
    'id_kelompok'    => $kelompok->id_kelompok ?? null,
    'id_tempat'      => $tempat->id_tempat ?? null,
    'id_dosen'       => $dosen->id_dosen ?? null,
        ]);

    }
}
