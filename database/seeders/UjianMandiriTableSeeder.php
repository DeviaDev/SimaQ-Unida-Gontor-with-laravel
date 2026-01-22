<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UjianMandiriTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('ujian_mandiri')->delete();
        
        \DB::table('ujian_mandiri')->insert(array (
            0 => 
            array (
                'id_ujian_mandiri' => 1,
                'id_mahasiswi' => 5,
                'materi' => 'Al-Baqarah 1-10',
                'keterangan_ujian' => 'Mandiri',
                'nilai' => 'b',
                'created_at' => '2026-01-06 05:52:26',
                'updated_at' => '2026-01-06 05:52:26',
            ),
            1 => 
            array (
                'id_ujian_mandiri' => 2,
                'id_mahasiswi' => 16,
                'materi' => 'Al-Baqarah 1-10',
                'keterangan_ujian' => 'Serentak',
                'nilai' => 'c',
                'created_at' => '2026-01-06 05:53:28',
                'updated_at' => '2026-01-06 05:53:28',
            ),
        ));
        
        
    }
}