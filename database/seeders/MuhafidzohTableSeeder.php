<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MuhafidzohTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('muhafidzoh')->delete();
        
        \DB::table('muhafidzoh')->insert(array (
            0 => 
            array (
                'id_muhafidzoh' => 2,
                'nama_muhafidzoh' => 'As-Sayyidah Syahidatun Duha',
                'keterangan' => 'PBA/1',
                'id_kelompok' => 1,
                'id_tempat' => 2,
                'created_at' => '2026-01-05 14:59:24',
                'updated_at' => '2026-01-05 14:59:24',
            ),
            1 => 
            array (
                'id_muhafidzoh' => 3,
                'nama_muhafidzoh' => 'As-Sayyidah Salsa Nur Farida',
                'keterangan' => 'PAI/1',
                'id_kelompok' => 2,
                'id_tempat' => 2,
                'created_at' => '2026-01-05 14:59:24',
                'updated_at' => '2026-01-05 14:59:24',
            ),
            2 => 
            array (
                'id_muhafidzoh' => 4,
                'nama_muhafidzoh' => 'As-Sayyidah Hana Ulayya',
                'keterangan' => 'TBI/3',
                'id_kelompok' => 3,
                'id_tempat' => 2,
                'created_at' => '2026-01-05 14:59:24',
                'updated_at' => '2026-01-05 14:59:24',
            ),
            3 => 
            array (
                'id_muhafidzoh' => 5,
                'nama_muhafidzoh' => 'As-Sayyidah Zhafira Inas Mufidah',
                'keterangan' => 'IQT/3',
                'id_kelompok' => 4,
                'id_tempat' => 1,
                'created_at' => '2026-01-05 14:59:24',
                'updated_at' => '2026-01-05 14:59:24',
            ),
            4 => 
            array (
                'id_muhafidzoh' => 6,
                'nama_muhafidzoh' => 'As-Sayyidah Anggun Rahmawati',
                'keterangan' => 'AFI/3',
                'id_kelompok' => 5,
                'id_tempat' => 1,
                'created_at' => '2026-01-05 14:59:24',
                'updated_at' => '2026-01-05 14:59:24',
            ),
            5 => 
            array (
                'id_muhafidzoh' => 7,
                'nama_muhafidzoh' => 'As-Sayyidah Hawa Wahyu Utami',
                'keterangan' => 'PM/3',
                'id_kelompok' => 6,
                'id_tempat' => 1,
                'created_at' => '2026-01-05 14:59:24',
                'updated_at' => '2026-01-05 14:59:24',
            ),
            6 => 
            array (
                'id_muhafidzoh' => 8,
                'nama_muhafidzoh' => 'As-Sayyidah Inayah Aqila',
                'keterangan' => 'HES/3',
                'id_kelompok' => 7,
                'id_tempat' => 4,
                'created_at' => '2026-01-05 14:59:24',
                'updated_at' => '2026-01-05 14:59:24',
            ),
            7 => 
            array (
                'id_muhafidzoh' => 9,
                'nama_muhafidzoh' => 'As-Sayyidah Abidatur Rahmah',
                'keterangan' => 'EI/3',
                'id_kelompok' => 8,
                'id_tempat' => 4,
                'created_at' => '2026-01-05 14:59:24',
                'updated_at' => '2026-01-05 14:59:24',
            ),
            8 => 
            array (
                'id_muhafidzoh' => 10,
                'nama_muhafidzoh' => 'As-Sayyidah Gevira Lia Rahman',
                'keterangan' => 'MB/5',
                'id_kelompok' => 9,
                'id_tempat' => 4,
                'created_at' => '2026-01-05 14:59:24',
                'updated_at' => '2026-01-05 14:59:24',
            ),
            9 => 
            array (
                'id_muhafidzoh' => 11,
                'nama_muhafidzoh' => 'As-Sayyidah Ratih Tri Cahyanti',
                'keterangan' => 'HI/5',
                'id_kelompok' => 10,
                'id_tempat' => 5,
                'created_at' => '2026-01-05 14:59:24',
                'updated_at' => '2026-01-05 14:59:24',
            ),
            10 => 
            array (
                'id_muhafidzoh' => 12,
                'nama_muhafidzoh' => 'As-Sayyidah Ghefira Azzura Tazkiya',
                'keterangan' => 'ILKOM/5',
                'id_kelompok' => 11,
                'id_tempat' => 5,
                'created_at' => '2026-01-05 14:59:24',
                'updated_at' => '2026-01-05 14:59:24',
            ),
            11 => 
            array (
                'id_muhafidzoh' => 13,
                'nama_muhafidzoh' => 'As-Sayyidah Bella Putri Rahmah',
                'keterangan' => 'FARM/5',
                'id_kelompok' => 12,
                'id_tempat' => 5,
                'created_at' => '2026-01-05 14:59:24',
                'updated_at' => '2026-01-05 14:59:24',
            ),
            12 => 
            array (
                'id_muhafidzoh' => 14,
                'nama_muhafidzoh' => 'As-Sayyidah Devika Juliana',
                'keterangan' => 'GIZI/7',
                'id_kelompok' => 13,
                'id_tempat' => 3,
                'created_at' => '2026-01-05 14:59:24',
                'updated_at' => '2026-01-05 14:59:24',
            ),
            13 => 
            array (
                'id_muhafidzoh' => 15,
                'nama_muhafidzoh' => 'As-Sayyidah Dina Rahayu',
                'keterangan' => 'TI/7',
                'id_kelompok' => 14,
                'id_tempat' => 3,
                'created_at' => '2026-01-05 14:59:24',
                'updated_at' => '2026-01-05 14:59:24',
            ),
            14 => 
            array (
                'id_muhafidzoh' => 16,
                'nama_muhafidzoh' => 'As-Sayyidah Aryu Yulandari',
                'keterangan' => 'AGRO/7',
                'id_kelompok' => 15,
                'id_tempat' => 3,
                'created_at' => '2026-01-05 14:59:24',
                'updated_at' => '2026-01-05 14:59:24',
            ),
        ));
        
        
    }
}