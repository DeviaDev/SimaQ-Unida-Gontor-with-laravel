<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AbsensisTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('absensis')->delete();
        
        \DB::table('absensis')->insert(array (
            0 => 
            array (
                'id' => 1,
                'id_mahasiswi' => 1,
                'pertemuan' => 1,
                'tanggal' => '2026-01-01',
                'status' => 'alpha',
                'prodi' => 'PBA',
                'semester' => 1,
                'kelompok' => '1',
                'created_at' => '2026-01-10 07:55:12',
                'updated_at' => '2026-01-10 07:55:15',
            ),
            1 => 
            array (
                'id' => 2,
                'id_mahasiswi' => 2,
                'pertemuan' => 1,
                'tanggal' => '2026-01-01',
                'status' => 'hadir',
                'prodi' => 'PBA',
                'semester' => 1,
                'kelompok' => '1',
                'created_at' => '2026-01-10 07:55:13',
                'updated_at' => '2026-01-10 07:55:15',
            ),
            2 => 
            array (
                'id' => 3,
                'id_mahasiswi' => 1,
                'pertemuan' => 2,
                'tanggal' => '2026-01-10',
                'status' => 'hadir',
                'prodi' => 'PBA',
                'semester' => 1,
                'kelompok' => '1',
                'created_at' => '2026-01-10 07:55:35',
                'updated_at' => '2026-01-10 07:55:37',
            ),
            3 => 
            array (
                'id' => 4,
                'id_mahasiswi' => 2,
                'pertemuan' => 2,
                'tanggal' => '2026-01-10',
                'status' => 'hadir',
                'prodi' => 'PBA',
                'semester' => 1,
                'kelompok' => '1',
                'created_at' => '2026-01-10 07:55:35',
                'updated_at' => '2026-01-10 07:55:37',
            ),
        ));
        
        
    }
}