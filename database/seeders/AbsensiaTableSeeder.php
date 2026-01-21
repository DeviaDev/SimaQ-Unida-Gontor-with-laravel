<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AbsensiaTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('absensia')->delete();
        
        \DB::table('absensia')->insert(array (
            0 => 
            array (
                'id' => 1,
                'id_muhafidzoh' => 13,
                'pertemuan' => 1,
                'tanggal' => '2026-01-01',
                'status' => 'hadir',
                'gedung' => 'Aula Unida',
                'created_at' => '2026-01-09 12:41:11',
                'updated_at' => '2026-01-09 12:41:11',
            ),
            1 => 
            array (
                'id' => 2,
                'id_muhafidzoh' => 12,
                'pertemuan' => 1,
                'tanggal' => '2026-01-01',
                'status' => 'hadir',
                'gedung' => 'Aula Unida',
                'created_at' => '2026-01-09 12:41:11',
                'updated_at' => '2026-01-09 12:41:11',
            ),
            2 => 
            array (
                'id' => 3,
                'id_muhafidzoh' => 11,
                'pertemuan' => 1,
                'tanggal' => '2026-01-01',
                'status' => 'hadir',
                'gedung' => 'Aula Unida',
                'created_at' => '2026-01-09 12:41:12',
                'updated_at' => '2026-01-09 12:41:12',
            ),
            3 => 
            array (
                'id' => 7,
                'id_muhafidzoh' => 16,
                'pertemuan' => 1,
                'tanggal' => '2026-01-01',
                'status' => 'alpha',
                'gedung' => 'Musholla',
                'created_at' => '2026-01-09 12:41:46',
                'updated_at' => '2026-01-09 12:41:46',
            ),
            4 => 
            array (
                'id' => 8,
                'id_muhafidzoh' => 15,
                'pertemuan' => 1,
                'tanggal' => '2026-01-01',
                'status' => 'alpha',
                'gedung' => 'Musholla',
                'created_at' => '2026-01-09 12:41:46',
                'updated_at' => '2026-01-09 12:41:46',
            ),
            5 => 
            array (
                'id' => 9,
                'id_muhafidzoh' => 14,
                'pertemuan' => 1,
                'tanggal' => '2026-01-01',
                'status' => 'hadir',
                'gedung' => 'Musholla',
                'created_at' => '2026-01-09 12:41:46',
                'updated_at' => '2026-01-09 12:41:48',
            ),
            6 => 
            array (
                'id' => 10,
                'id_muhafidzoh' => 16,
                'pertemuan' => 2,
                'tanggal' => '2026-01-02',
                'status' => 'hadir',
                'gedung' => 'Musholla',
                'created_at' => '2026-01-09 12:41:56',
                'updated_at' => '2026-01-09 12:41:56',
            ),
            7 => 
            array (
                'id' => 11,
                'id_muhafidzoh' => 14,
                'pertemuan' => 2,
                'tanggal' => '2026-01-02',
                'status' => 'hadir',
                'gedung' => 'Musholla',
                'created_at' => '2026-01-09 12:41:56',
                'updated_at' => '2026-01-09 12:41:56',
            ),
            8 => 
            array (
                'id' => 12,
                'id_muhafidzoh' => 15,
                'pertemuan' => 2,
                'tanggal' => '2026-01-02',
                'status' => 'hadir',
                'gedung' => 'Musholla',
                'created_at' => '2026-01-09 12:41:56',
                'updated_at' => '2026-01-09 12:41:56',
            ),
            9 => 
            array (
                'id' => 13,
                'id_muhafidzoh' => 9,
                'pertemuan' => 1,
                'tanggal' => '2026-01-01',
                'status' => 'hadir',
                'gedung' => 'Klaster',
                'created_at' => '2026-01-09 12:42:06',
                'updated_at' => '2026-01-09 12:42:06',
            ),
            10 => 
            array (
                'id' => 14,
                'id_muhafidzoh' => 8,
                'pertemuan' => 1,
                'tanggal' => '2026-01-01',
                'status' => 'alpha',
                'gedung' => 'Klaster',
                'created_at' => '2026-01-09 12:42:06',
                'updated_at' => '2026-01-09 12:42:10',
            ),
            11 => 
            array (
                'id' => 15,
                'id_muhafidzoh' => 10,
                'pertemuan' => 1,
                'tanggal' => '2026-01-01',
                'status' => 'izin',
                'gedung' => 'Klaster',
                'created_at' => '2026-01-09 12:42:06',
                'updated_at' => '2026-01-09 12:42:10',
            ),
            12 => 
            array (
                'id' => 16,
                'id_muhafidzoh' => 8,
                'pertemuan' => 2,
                'tanggal' => '2026-01-02',
                'status' => 'hadir',
                'gedung' => 'Klaster',
                'created_at' => '2026-01-09 12:44:19',
                'updated_at' => '2026-01-09 12:44:19',
            ),
            13 => 
            array (
                'id' => 17,
                'id_muhafidzoh' => 9,
                'pertemuan' => 2,
                'tanggal' => '2026-01-02',
                'status' => 'hadir',
                'gedung' => 'Klaster',
                'created_at' => '2026-01-09 12:44:19',
                'updated_at' => '2026-01-09 12:44:19',
            ),
            14 => 
            array (
                'id' => 18,
                'id_muhafidzoh' => 10,
                'pertemuan' => 2,
                'tanggal' => '2026-01-02',
                'status' => 'hadir',
                'gedung' => 'Klaster',
                'created_at' => '2026-01-09 12:44:19',
                'updated_at' => '2026-01-09 12:44:19',
            ),
            15 => 
            array (
                'id' => 19,
                'id_muhafidzoh' => 4,
                'pertemuan' => 1,
                'tanggal' => '2026-01-01',
                'status' => 'izin',
                'gedung' => 'Istanbul LT3',
                'created_at' => '2026-01-09 12:44:33',
                'updated_at' => '2026-01-09 12:44:33',
            ),
            16 => 
            array (
                'id' => 20,
                'id_muhafidzoh' => 3,
                'pertemuan' => 1,
                'tanggal' => '2026-01-01',
                'status' => 'hadir',
                'gedung' => 'Istanbul LT3',
                'created_at' => '2026-01-09 12:44:33',
                'updated_at' => '2026-01-09 12:44:35',
            ),
            17 => 
            array (
                'id' => 21,
                'id_muhafidzoh' => 2,
                'pertemuan' => 1,
                'tanggal' => '2026-01-01',
                'status' => 'izin',
                'gedung' => 'Istanbul LT3',
                'created_at' => '2026-01-09 12:44:33',
                'updated_at' => '2026-01-09 12:44:33',
            ),
            18 => 
            array (
                'id' => 22,
                'id_muhafidzoh' => 3,
                'pertemuan' => 2,
                'tanggal' => '2026-01-02',
                'status' => 'hadir',
                'gedung' => 'Istanbul LT3',
                'created_at' => '2026-01-09 12:44:44',
                'updated_at' => '2026-01-09 12:44:44',
            ),
            19 => 
            array (
                'id' => 23,
                'id_muhafidzoh' => 2,
                'pertemuan' => 2,
                'tanggal' => '2026-01-02',
                'status' => 'hadir',
                'gedung' => 'Istanbul LT3',
                'created_at' => '2026-01-09 12:44:44',
                'updated_at' => '2026-01-09 12:44:44',
            ),
            20 => 
            array (
                'id' => 24,
                'id_muhafidzoh' => 4,
                'pertemuan' => 2,
                'tanggal' => '2026-01-02',
                'status' => 'hadir',
                'gedung' => 'Istanbul LT3',
                'created_at' => '2026-01-09 12:44:44',
                'updated_at' => '2026-01-09 12:44:44',
            ),
            21 => 
            array (
                'id' => 25,
                'id_muhafidzoh' => 7,
                'pertemuan' => 1,
                'tanggal' => '2026-01-01',
                'status' => 'hadir',
                'gedung' => 'Istanbul LT2',
                'created_at' => '2026-01-09 12:44:55',
                'updated_at' => '2026-01-09 12:44:55',
            ),
            22 => 
            array (
                'id' => 26,
                'id_muhafidzoh' => 6,
                'pertemuan' => 1,
                'tanggal' => '2026-01-01',
                'status' => 'alpha',
                'gedung' => 'Istanbul LT2',
                'created_at' => '2026-01-09 12:44:55',
                'updated_at' => '2026-01-09 12:44:57',
            ),
            23 => 
            array (
                'id' => 27,
                'id_muhafidzoh' => 5,
                'pertemuan' => 1,
                'tanggal' => '2026-01-01',
                'status' => 'hadir',
                'gedung' => 'Istanbul LT2',
                'created_at' => '2026-01-09 12:44:55',
                'updated_at' => '2026-01-09 12:44:55',
            ),
            24 => 
            array (
                'id' => 28,
                'id_muhafidzoh' => 6,
                'pertemuan' => 2,
                'tanggal' => '2026-01-02',
                'status' => 'hadir',
                'gedung' => 'Istanbul LT2',
                'created_at' => '2026-01-09 12:45:05',
                'updated_at' => '2026-01-09 12:45:06',
            ),
            25 => 
            array (
                'id' => 29,
                'id_muhafidzoh' => 7,
                'pertemuan' => 2,
                'tanggal' => '2026-01-02',
                'status' => 'hadir',
                'gedung' => 'Istanbul LT2',
                'created_at' => '2026-01-09 12:45:05',
                'updated_at' => '2026-01-09 12:45:07',
            ),
            26 => 
            array (
                'id' => 30,
                'id_muhafidzoh' => 5,
                'pertemuan' => 2,
                'tanggal' => '2026-01-02',
                'status' => 'izin',
                'gedung' => 'Istanbul LT2',
                'created_at' => '2026-01-09 12:45:05',
                'updated_at' => '2026-01-09 12:45:05',
            ),
        ));
        
        
    }
}