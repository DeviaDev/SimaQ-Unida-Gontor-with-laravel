<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TempatTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tempat')->delete();
        
        \DB::table('tempat')->insert(array (
            0 => 
            array (
                'id_tempat' => 1,
                'nama_tempat' => 'Istanbul LT2',
                'created_at' => '2026-01-05 14:51:55',
                'updated_at' => '2026-01-05 14:51:55',
            ),
            1 => 
            array (
                'id_tempat' => 2,
                'nama_tempat' => 'Istanbul LT3',
                'created_at' => '2026-01-05 14:51:55',
                'updated_at' => '2026-01-05 14:51:55',
            ),
            2 => 
            array (
                'id_tempat' => 3,
                'nama_tempat' => 'Musholla',
                'created_at' => '2026-01-05 14:51:55',
                'updated_at' => '2026-01-05 14:51:55',
            ),
            3 => 
            array (
                'id_tempat' => 4,
                'nama_tempat' => 'Klaster',
                'created_at' => '2026-01-05 14:51:55',
                'updated_at' => '2026-01-05 14:51:55',
            ),
            4 => 
            array (
                'id_tempat' => 5,
                'nama_tempat' => 'Aula Unida',
                'created_at' => '2026-01-05 14:51:55',
                'updated_at' => '2026-01-05 14:51:55',
            ),
        ));
        
        
    }
}