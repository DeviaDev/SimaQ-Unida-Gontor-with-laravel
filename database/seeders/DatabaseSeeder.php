<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      
        User::create([
            'name'     => 'Test User',
            'email'    => 'test@example.com',
            'password' => Hash::make('123123123')
        ]);
        $this->call(AbsensiaTableSeeder::class);
        $this->call(AbsensisTableSeeder::class);
        $this->call(AbsensiPengurusTableSeeder::class);
        $this->call(DosenTableSeeder::class);
        $this->call(MahasiswiTableSeeder::class);
        $this->call(PengurusTableSeeder::class);
        $this->call(LaporanKegiatanTableSeeder::class);
        $this->call(MahatilawahTableSeeder::class);
        $this->call(MuhafidzohTableSeeder::class);
        $this->call(KelompokLtTableSeeder::class);
        $this->call(TempatTableSeeder::class);
        $this->call(TilawahPengurusTableSeeder::class);
        $this->call(UjianMandiriTableSeeder::class);
        $this->call(UjianTahsinTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
