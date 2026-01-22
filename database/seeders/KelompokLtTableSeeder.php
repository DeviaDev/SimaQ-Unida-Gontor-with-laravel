<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class KelompokLtTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('kelompok_lt')->delete();
        
        \DB::table('kelompok_lt')->insert(array (
            0 => 
            array (
                'id_kelompok' => 1,
                'kode_kelompok' => 'PBA1A',
                'id_tempat' => 1,
                'created_at' => '2026-01-05 14:55:39',
                'updated_at' => '2026-01-05 14:55:39',
            ),
            1 => 
            array (
                'id_kelompok' => 2,
                'kode_kelompok' => 'PAI1A',
                'id_tempat' => 1,
                'created_at' => '2026-01-05 14:55:39',
                'updated_at' => '2026-01-05 14:55:39',
            ),
            2 => 
            array (
                'id_kelompok' => 3,
                'kode_kelompok' => 'TBI3A',
                'id_tempat' => 1,
                'created_at' => '2026-01-05 14:55:39',
                'updated_at' => '2026-01-05 14:55:39',
            ),
            3 => 
            array (
                'id_kelompok' => 4,
                'kode_kelompok' => 'IQT3A',
                'id_tempat' => 2,
                'created_at' => '2026-01-05 14:55:39',
                'updated_at' => '2026-01-05 14:55:39',
            ),
            4 => 
            array (
                'id_kelompok' => 5,
                'kode_kelompok' => 'AFI3A',
                'id_tempat' => 2,
                'created_at' => '2026-01-05 14:55:39',
                'updated_at' => '2026-01-05 14:55:39',
            ),
            5 => 
            array (
                'id_kelompok' => 6,
                'kode_kelompok' => 'PM3A',
                'id_tempat' => 2,
                'created_at' => '2026-01-05 14:55:39',
                'updated_at' => '2026-01-05 14:55:39',
            ),
            6 => 
            array (
                'id_kelompok' => 7,
                'kode_kelompok' => 'HES3A',
                'id_tempat' => 3,
                'created_at' => '2026-01-05 14:55:39',
                'updated_at' => '2026-01-05 14:55:39',
            ),
            7 => 
            array (
                'id_kelompok' => 8,
                'kode_kelompok' => 'EI3A',
                'id_tempat' => 3,
                'created_at' => '2026-01-05 14:55:39',
                'updated_at' => '2026-01-05 14:55:39',
            ),
            8 => 
            array (
                'id_kelompok' => 9,
                'kode_kelompok' => 'MB5A',
                'id_tempat' => 3,
                'created_at' => '2026-01-05 14:55:39',
                'updated_at' => '2026-01-05 14:55:39',
            ),
            9 => 
            array (
                'id_kelompok' => 10,
                'kode_kelompok' => 'HI5A',
                'id_tempat' => 4,
                'created_at' => '2026-01-05 14:55:39',
                'updated_at' => '2026-01-05 14:55:39',
            ),
            10 => 
            array (
                'id_kelompok' => 11,
                'kode_kelompok' => 'ILKOM5A',
                'id_tempat' => 4,
                'created_at' => '2026-01-05 14:55:39',
                'updated_at' => '2026-01-05 14:55:39',
            ),
            11 => 
            array (
                'id_kelompok' => 12,
                'kode_kelompok' => 'FARM5A',
                'id_tempat' => 4,
                'created_at' => '2026-01-05 14:55:39',
                'updated_at' => '2026-01-05 14:55:39',
            ),
            12 => 
            array (
                'id_kelompok' => 13,
                'kode_kelompok' => 'GIZI7A',
                'id_tempat' => 5,
                'created_at' => '2026-01-05 14:55:39',
                'updated_at' => '2026-01-05 14:55:39',
            ),
            13 => 
            array (
                'id_kelompok' => 14,
                'kode_kelompok' => 'TI7A',
                'id_tempat' => 5,
                'created_at' => '2026-01-05 14:55:39',
                'updated_at' => '2026-01-05 14:55:39',
            ),
            14 => 
            array (
                'id_kelompok' => 15,
                'kode_kelompok' => 'AGRO7A',
                'id_tempat' => 5,
                'created_at' => '2026-01-05 14:55:39',
                'updated_at' => '2026-01-05 14:55:39',
            ),
        ));
        
        
    }
}