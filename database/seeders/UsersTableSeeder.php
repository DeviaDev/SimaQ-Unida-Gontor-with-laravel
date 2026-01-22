<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 2,
                'name' => 'Admin Markaz',
                'email' => 'adminmarkaz@gmail.com',
                'password' => '$2y$12$oip6KG.u8sJojph6YB5/MODLW2LJoxw/F85q66Xq3kWjI2ALdlpE6',
                'remember_token' => NULL,
                'created_at' => '2025-10-06 12:23:43',
                'updated_at' => '2025-10-06 12:23:43',
            ),
            1 => 
            array (
                'id' => 3,
                'name' => 'Admin Markaz Quran',
                'email' => 'admin@gmail.com',
                'password' => '$2y$12$PcDnV/lfA/TSY2TC4WKJ2OuI7CVkA.WqsRhuMYZb8ekMx.GZLSfFy',
                'remember_token' => NULL,
                'created_at' => '2025-12-22 05:43:49',
                'updated_at' => '2025-12-22 05:43:49',
            ),
        ));
        
        
    }
}