<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MahasiswiTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('mahasiswi')->delete();
        
        \DB::table('mahasiswi')->insert(array (
            0 => 
            array (
                'id_mahasiswi' => 1,
                'nama_mahasiswi' => 'AFIFAH ISHMATUL AULIYA',
                'prodi' => 'PBA',
                'semester' => 1,
                'id_muhafidzoh' => 2,
                'id_dosen' => 1,
                'id_kelompok' => 1,
                'id_tempat' => 2,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            1 => 
            array (
                'id_mahasiswi' => 2,
                'nama_mahasiswi' => 'AISYA REZKI APRILIA',
                'prodi' => 'PBA',
                'semester' => 1,
                'id_muhafidzoh' => 2,
                'id_dosen' => 1,
                'id_kelompok' => 1,
                'id_tempat' => 2,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            2 => 
            array (
                'id_mahasiswi' => 3,
                'nama_mahasiswi' => 'ANANDYA NUR ZAHIDAH',
                'prodi' => 'PAI',
                'semester' => 1,
                'id_muhafidzoh' => 3,
                'id_dosen' => 1,
                'id_kelompok' => 2,
                'id_tempat' => 2,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            3 => 
            array (
                'id_mahasiswi' => 4,
                'nama_mahasiswi' => 'ATHAYA LAHFAH KHALILAH',
                'prodi' => 'PAI',
                'semester' => 1,
                'id_muhafidzoh' => 3,
                'id_dosen' => 1,
                'id_kelompok' => 2,
                'id_tempat' => 2,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            4 => 
            array (
                'id_mahasiswi' => 5,
                'nama_mahasiswi' => 'CALLYEA NAJWA',
                'prodi' => 'TBI',
                'semester' => 3,
                'id_muhafidzoh' => 4,
                'id_dosen' => 2,
                'id_kelompok' => 3,
                'id_tempat' => 2,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            5 => 
            array (
                'id_mahasiswi' => 6,
                'nama_mahasiswi' => 'DEVITA WAHYU SRIASTUTI',
                'prodi' => 'TBI',
                'semester' => 3,
                'id_muhafidzoh' => 4,
                'id_dosen' => 2,
                'id_kelompok' => 3,
                'id_tempat' => 2,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            6 => 
            array (
                'id_mahasiswi' => 7,
                'nama_mahasiswi' => 'FANI ANAS TASYA',
                'prodi' => 'IQT',
                'semester' => 3,
                'id_muhafidzoh' => 5,
                'id_dosen' => 2,
                'id_kelompok' => 4,
                'id_tempat' => 1,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 10:32:01',
            ),
            7 => 
            array (
                'id_mahasiswi' => 8,
                'nama_mahasiswi' => 'FARISKA NURUL IZZA',
                'prodi' => 'IQT',
                'semester' => 3,
                'id_muhafidzoh' => 5,
                'id_dosen' => 2,
                'id_kelompok' => 4,
                'id_tempat' => 1,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            8 => 
            array (
                'id_mahasiswi' => 9,
                'nama_mahasiswi' => 'NABILA AMALIA PUTRI',
                'prodi' => 'AFI',
                'semester' => 3,
                'id_muhafidzoh' => 6,
                'id_dosen' => 3,
                'id_kelompok' => 5,
                'id_tempat' => 1,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            9 => 
            array (
                'id_mahasiswi' => 10,
                'nama_mahasiswi' => 'NESYA ADYA PUTRI',
                'prodi' => 'AFI',
                'semester' => 3,
                'id_muhafidzoh' => 6,
                'id_dosen' => 3,
                'id_kelompok' => 5,
                'id_tempat' => 1,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            10 => 
            array (
                'id_mahasiswi' => 11,
                'nama_mahasiswi' => 'DIAH AYU LAILATUL ISHAQ',
                'prodi' => 'PM',
                'semester' => 3,
                'id_muhafidzoh' => 7,
                'id_dosen' => 3,
                'id_kelompok' => 6,
                'id_tempat' => 1,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            11 => 
            array (
                'id_mahasiswi' => 12,
                'nama_mahasiswi' => 'JASMINE ZANETTA SHAUMI',
                'prodi' => 'PM',
                'semester' => 3,
                'id_muhafidzoh' => 7,
                'id_dosen' => 3,
                'id_kelompok' => 6,
                'id_tempat' => 1,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            12 => 
            array (
                'id_mahasiswi' => 13,
                'nama_mahasiswi' => 'NAILA REVA',
                'prodi' => 'HES',
                'semester' => 3,
                'id_muhafidzoh' => 8,
                'id_dosen' => 4,
                'id_kelompok' => 7,
                'id_tempat' => 4,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            13 => 
            array (
                'id_mahasiswi' => 14,
                'nama_mahasiswi' => 'NISHA FADILLAH',
                'prodi' => 'HES',
                'semester' => 3,
                'id_muhafidzoh' => 8,
                'id_dosen' => 4,
                'id_kelompok' => 7,
                'id_tempat' => 4,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            14 => 
            array (
                'id_mahasiswi' => 15,
                'nama_mahasiswi' => 'REFAT JAHABIDZA',
                'prodi' => 'EI',
                'semester' => 3,
                'id_muhafidzoh' => 9,
                'id_dosen' => 4,
                'id_kelompok' => 8,
                'id_tempat' => 4,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            15 => 
            array (
                'id_mahasiswi' => 16,
                'nama_mahasiswi' => 'RIFDAH SEPTIANI',
                'prodi' => 'EI',
                'semester' => 3,
                'id_muhafidzoh' => 9,
                'id_dosen' => 4,
                'id_kelompok' => 8,
                'id_tempat' => 4,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            16 => 
            array (
                'id_mahasiswi' => 17,
                'nama_mahasiswi' => 'RIHA AULIYA',
                'prodi' => 'MB',
                'semester' => 5,
                'id_muhafidzoh' => 10,
                'id_dosen' => 5,
                'id_kelompok' => 9,
                'id_tempat' => 4,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            17 => 
            array (
                'id_mahasiswi' => 18,
                'nama_mahasiswi' => 'RISYA FADIYA PRAMESTI SYAHARANI',
                'prodi' => 'MB',
                'semester' => 5,
                'id_muhafidzoh' => 10,
                'id_dosen' => 5,
                'id_kelompok' => 9,
                'id_tempat' => 4,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            18 => 
            array (
                'id_mahasiswi' => 19,
                'nama_mahasiswi' => 'ROZAN MAULIDINA HARTO',
                'prodi' => 'HI',
                'semester' => 5,
                'id_muhafidzoh' => 11,
                'id_dosen' => 5,
                'id_kelompok' => 10,
                'id_tempat' => 5,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            19 => 
            array (
                'id_mahasiswi' => 20,
                'nama_mahasiswi' => 'SALMA NADA FATMA',
                'prodi' => 'HI',
                'semester' => 5,
                'id_muhafidzoh' => 11,
                'id_dosen' => 5,
                'id_kelompok' => 10,
                'id_tempat' => 5,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            20 => 
            array (
                'id_mahasiswi' => 21,
                'nama_mahasiswi' => 'AGHISNA FAUZUL HAKIM',
                'prodi' => 'ILKOM',
                'semester' => 5,
                'id_muhafidzoh' => 12,
                'id_dosen' => 6,
                'id_kelompok' => 11,
                'id_tempat' => 5,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            21 => 
            array (
                'id_mahasiswi' => 22,
                'nama_mahasiswi' => 'BATARA TENRIAKAYLA FIRZANAH FAHMI',
                'prodi' => 'ILKOM',
                'semester' => 5,
                'id_muhafidzoh' => 12,
                'id_dosen' => 6,
                'id_kelompok' => 11,
                'id_tempat' => 5,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            22 => 
            array (
                'id_mahasiswi' => 23,
                'nama_mahasiswi' => 'BRILLIANT AGUSTIN EKA NIDA',
                'prodi' => 'FARM',
                'semester' => 5,
                'id_muhafidzoh' => 13,
                'id_dosen' => 6,
                'id_kelompok' => 12,
                'id_tempat' => 5,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            23 => 
            array (
                'id_mahasiswi' => 24,
                'nama_mahasiswi' => 'CELSIE BUNGA FADILAH',
                'prodi' => 'FARM',
                'semester' => 5,
                'id_muhafidzoh' => 13,
                'id_dosen' => 6,
                'id_kelompok' => 12,
                'id_tempat' => 5,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            24 => 
            array (
                'id_mahasiswi' => 25,
                'nama_mahasiswi' => 'FAREN DINA AISYAH',
                'prodi' => 'GIZI',
                'semester' => 7,
                'id_muhafidzoh' => 14,
                'id_dosen' => 7,
                'id_kelompok' => 13,
                'id_tempat' => 3,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            25 => 
            array (
                'id_mahasiswi' => 26,
                'nama_mahasiswi' => 'JULIANDA ARDELIA',
                'prodi' => 'GIZI',
                'semester' => 7,
                'id_muhafidzoh' => 14,
                'id_dosen' => 7,
                'id_kelompok' => 13,
                'id_tempat' => 3,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            26 => 
            array (
                'id_mahasiswi' => 27,
                'nama_mahasiswi' => 'KARINA PRAMESWARI',
                'prodi' => 'TI',
                'semester' => 7,
                'id_muhafidzoh' => 15,
                'id_dosen' => 7,
                'id_kelompok' => 14,
                'id_tempat' => 3,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            27 => 
            array (
                'id_mahasiswi' => 28,
                'nama_mahasiswi' => 'LAELATUL FADHILAH RAMADHANI',
                'prodi' => 'TI',
                'semester' => 7,
                'id_muhafidzoh' => 15,
                'id_dosen' => 7,
                'id_kelompok' => 14,
                'id_tempat' => 3,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            28 => 
            array (
                'id_mahasiswi' => 29,
                'nama_mahasiswi' => 'NAFIZA MAWADAH',
                'prodi' => 'AGRO',
                'semester' => 7,
                'id_muhafidzoh' => NULL,
                'id_dosen' => 8,
                'id_kelompok' => 15,
                'id_tempat' => 3,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            29 => 
            array (
                'id_mahasiswi' => 30,
                'nama_mahasiswi' => 'NAJWA MADANIA',
                'prodi' => 'AGRO',
                'semester' => 7,
                'id_muhafidzoh' => NULL,
                'id_dosen' => 8,
                'id_kelompok' => 15,
                'id_tempat' => 3,
                'created_at' => '2026-01-05 08:14:36',
                'updated_at' => '2026-01-05 08:14:36',
            ),
            30 => 
            array (
                'id_mahasiswi' => 31,
                'nama_mahasiswi' => 'NASYWA KYNDA',
                'prodi' => 'TI',
                'semester' => 7,
                'id_muhafidzoh' => 12,
                'id_dosen' => 7,
                'id_kelompok' => 14,
                'id_tempat' => 3,
                'created_at' => '2026-01-05 10:33:17',
                'updated_at' => '2026-01-05 10:33:17',
            ),
        ));
        
        
    }
}