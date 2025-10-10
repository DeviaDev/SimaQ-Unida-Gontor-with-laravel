<?php

namespace App\Imports;

use App\Models\Pengurus;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PengurusImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Pastikan data penting tidak kosong
        if (empty($row['nama']) || empty($row['email'])) {
            return null; // skip baris kosong
        }

        return new Pengurus([
            'foto'  => $row['foto'] ?? null,  // boleh kosong
            'nama'  => $row['nama'],
            'email' => $row['email'],
        ]);
    }
}
