<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\KelompokLT;
use App\Models\Muhafidzoh;
use App\Models\Tempat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DosenImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {

        $normalized = [];
        foreach ($row as $key => $value) {
            $key = strtolower(str_replace([' ', '-'], '_', trim($key)));
            $normalized[$key] = $value;
        }

        // Cegah data kosong agar tidak error
        if (empty($normalized['nama_dosen'])) {
            return null; // lewati baris kosong
        }

        // Cari relasi berdasarkan nama di Excel
        $kelompok = KelompokLT::where('kode_kelompok', $normalized['kelompok'])->first();
        $muhafidzoh = Muhafidzoh::where('nama_muhafidzoh', $normalized['nama_muhafidzoh'])->first();
        $tempat = Tempat::where('nama_tempat', $normalized['tempat'])->first();

        return new Dosen([
            'nama_dosen'   => $normalized['nama_dosen'],
            'id_kelompok'  => $kelompok ? $kelompok->id_kelompok : null,
            'id_muhafidzoh'=> $muhafidzoh ? $muhafidzoh->id_muhafidzoh : null,
            'id_tempat'    => $tempat ? $tempat->id_tempat : null,
        ]);
    }
}

