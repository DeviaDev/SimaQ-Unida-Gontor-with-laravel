<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MahatilawahTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('mahatilawah')->delete();
        
        \DB::table('mahatilawah')->insert(array (
            0 => 
            array (
                'id' => 1,
                'id_mahasiswi' => 3,
                'juz' => '8,11,16,25',
                'khatam_ke' => 2,
                'created_at' => '2026-01-05 21:01:50',
                'updated_at' => '2026-01-05 14:02:17',
            ),
            1 => 
            array (
                'id' => 2,
                'id_mahasiswi' => 4,
                'juz' => '9,11,13,17,22,27',
                'khatam_ke' => 1,
                'created_at' => '2026-01-05 21:01:50',
                'updated_at' => '2026-01-05 14:02:17',
            ),
            2 => 
            array (
                'id' => 3,
                'id_mahasiswi' => 1,
                'juz' => '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30',
                'khatam_ke' => 1,
                'created_at' => '2026-01-06 00:18:58',
                'updated_at' => '2026-01-05 17:19:24',
            ),
            3 => 
            array (
                'id' => 4,
                'id_mahasiswi' => 2,
                'juz' => '',
                'khatam_ke' => 1,
                'created_at' => '2026-01-06 00:18:58',
                'updated_at' => '2026-01-05 17:19:24',
            ),
            4 => 
            array (
                'id' => 5,
                'id_mahasiswi' => 11,
                'juz' => '11',
                'khatam_ke' => 1,
                'created_at' => '2026-01-10 01:15:14',
                'updated_at' => '2026-01-09 18:15:14',
            ),
            5 => 
            array (
                'id' => 6,
                'id_mahasiswi' => 12,
                'juz' => '',
                'khatam_ke' => 1,
                'created_at' => '2026-01-10 01:15:14',
                'updated_at' => '2026-01-09 18:15:14',
            ),
        ));
        
        
    }
}