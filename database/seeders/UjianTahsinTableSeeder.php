<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UjianTahsinTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('ujian_tahsin')->delete();
        
        \DB::table('ujian_tahsin')->insert(array (
            0 => 
            array (
                'id_tahsin' => 1,
                'id_mahasiswi' => 3,
                'prodi' => NULL,
                'semester' => NULL,
                'kategori' => NULL,
                'materi' => 'yasin 1-10',
                'nilai' => 'B+',
                'keterangan_ujian' => NULL,
                'created_at' => '2026-01-06 05:56:44',
                'updated_at' => '2026-01-06 05:56:44',
            ),
        ));
        
        
    }
}