<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DosenTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('dosen')->delete();
        
        \DB::table('dosen')->insert(array (
            0 => 
            array (
                'id_dosen' => 1,
                'nama_dosen' => 'Al-Ustadzah Cela Petty Susanti, M.Pd.',
                'id_kelompok' => 1,
                'id_muhafidzoh' => 2,
                'id_tempat' => 2,
                'created_at' => '2026-01-05 14:59:02',
                'updated_at' => '2026-01-05 14:59:02',
            ),
            1 => 
            array (
                'id_dosen' => 2,
                'nama_dosen' => 'Al-Ustadzah Mutiara Dewi, M.Pd',
                'id_kelompok' => 3,
                'id_muhafidzoh' => 4,
                'id_tempat' => 2,
                'created_at' => '2026-01-05 14:59:02',
                'updated_at' => '2026-01-05 14:59:02',
            ),
            2 => 
            array (
                'id_dosen' => 3,
                'nama_dosen' => 'Al-Ustadzah Fathimah Kamilatun Nisa, M.Pd',
                'id_kelompok' => 5,
                'id_muhafidzoh' => 6,
                'id_tempat' => 1,
                'created_at' => '2026-01-05 14:59:02',
                'updated_at' => '2026-01-05 14:59:02',
            ),
            3 => 
            array (
                'id_dosen' => 4,
                'nama_dosen' => 'Al-Ustadzah Astuti Sifa\'urrohmah, M.Pd.',
                'id_kelompok' => 7,
                'id_muhafidzoh' => 8,
                'id_tempat' => 4,
                'created_at' => '2026-01-05 14:59:02',
                'updated_at' => '2026-01-05 14:59:02',
            ),
            4 => 
            array (
                'id_dosen' => 5,
                'nama_dosen' => 'Al-Ustadzah Siti Nikmatul Rochma, S.Pd., M.Pd',
                'id_kelompok' => 9,
                'id_muhafidzoh' => 10,
                'id_tempat' => 4,
                'created_at' => '2026-01-05 14:59:02',
                'updated_at' => '2026-01-05 14:59:02',
            ),
            5 => 
            array (
                'id_dosen' => 6,
                'nama_dosen' => 'Al-Ustadzah Alifah Yasmin, S.Ag., M.Ag.',
                'id_kelompok' => 11,
                'id_muhafidzoh' => 12,
                'id_tempat' => 5,
                'created_at' => '2026-01-05 14:59:02',
                'updated_at' => '2026-01-05 14:59:02',
            ),
            6 => 
            array (
                'id_dosen' => 7,
                'nama_dosen' => 'Al-Ustadzah Zalfaa\' \'Afaaf Zhoofiroh, M.Ag.',
                'id_kelompok' => 13,
                'id_muhafidzoh' => 14,
                'id_tempat' => 3,
                'created_at' => '2026-01-05 14:59:02',
                'updated_at' => '2026-01-05 14:59:02',
            ),
            7 => 
            array (
                'id_dosen' => 8,
                'nama_dosen' => 'Al-Ustadzah Dzatu Aliviatin Nuha, M.Ag.',
                'id_kelompok' => 15,
                'id_muhafidzoh' => 16,
                'id_tempat' => 3,
                'created_at' => '2026-01-05 14:59:02',
                'updated_at' => '2026-01-05 14:59:02',
            ),
        ));
        
        
    }
}