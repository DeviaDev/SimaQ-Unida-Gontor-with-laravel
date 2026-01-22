<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LaporanKegiatanTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('laporan_kegiatan')->delete();
        
        \DB::table('laporan_kegiatan')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nama_kegiatan' => 'MQ berbagi',
                'tanggal' => '2026-01-07',
                'waktu' => '00:27:00',
                'tempat' => 'Tol',
                'link_foto' => 'https://chatgpt.com/c/695b6d84-0dec-8330-bf35-4b2fd78f8adc',
                'berita_acara' => 'Kegiatan MQ Berbagi kembali digelar sebagai bentuk nyata kepedulian sosial dan semangat berbagi kepada sesama. Program ini diikuti oleh para panitia dan peserta yang dengan antusias terlibat sejak persiapan hingga pelaksanaan.

Kegiatan dimulai dengan pembukaan dan doa bersama, dilanjutkan dengan penyerahan bantuan secara langsung kepada penerima manfaat. Seluruh rangkaian berlangsung tertib, hangat, dan penuh kekeluargaan.

Melalui kegiatan ini, panitia berharap nilai kepedulian, empati, dan tanggung jawab sosial dapat terus tumbuh dan menjadi budaya bersama.

“Kami ingin bukan hanya berbagi bantuan, tapi juga berbagi kebahagiaan,” ujar salah satu panitia.

Acara ditutup dengan evaluasi singkat serta dokumentasi bersama, sebagai bentuk pertanggungjawaban sekaligus kenangan.',
                'created_at' => '2026-01-05 15:28:57',
                'updated_at' => '2026-01-05 15:28:57',
            ),
            1 => 
            array (
                'id' => 2,
                'nama_kegiatan' => 'Lalilatul Tahfidz 1',
                'tanggal' => '2026-01-08',
                'waktu' => '06:20:00',
                'tempat' => 'Musholla',
                'link_foto' => 'http://simak.test/laporan-kegiatan',
                'berita_acara' => 'hai',
                'created_at' => '2026-01-05 17:20:26',
                'updated_at' => '2026-01-05 17:20:26',
            ),
            2 => 
            array (
                'id' => 3,
                'nama_kegiatan' => 'kajian',
                'tanggal' => '2026-01-07',
                'waktu' => '07:07:00',
                'tempat' => 'musholla',
                'link_foto' => '-',
                'berita_acara' => '-',
                'created_at' => '2026-01-06 06:15:06',
                'updated_at' => '2026-01-06 06:15:06',
            ),
        ));
        
        
    }
}