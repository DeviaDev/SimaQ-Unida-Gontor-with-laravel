<?php

namespace App\Imports;

use App\Models\Tempat;
use App\Models\KelompokLT;
use App\Models\Muhafidzoh;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MuhafidzohImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Normalisasi header: ubah "Nama Muhafidzoh" → "nama_muhafidzoh"
        $normalized = [];
        foreach ($row as $key => $value) {
            $key = strtolower(str_replace([' ', '-'], '_', trim($key)));
            $normalized[$key] = $value;
        }

        // Cegah data kosong agar tidak error
        if (empty($normalized['nama_muhafidzoh'])) {
            return null; // lewati baris kosong
        }

        $kelompok = KelompokLT::where('kode_kelompok', $normalized['kelompok'] ?? null)->first();
        $tempat = Tempat::where('nama_tempat', $normalized['tempat'] ?? null)->first();

        return new Muhafidzoh([
            'nama_muhafidzoh' => $normalized['nama_muhafidzoh'] ?? '-',
            'keterangan'      => $normalized['keterangan'] ?? '-',
            'id_kelompok'     => $kelompok->id_kelompok ?? null,
            'id_tempat'       => $tempat->id_tempat ?? null,
        ]);
    }
}
