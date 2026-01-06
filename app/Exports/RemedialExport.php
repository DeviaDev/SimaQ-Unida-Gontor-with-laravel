<?php

namespace App\Exports;

// Panggil Model Remedial, bukan Ujiantahsin
use App\Models\Remedial; 
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class RemedialExport implements FromView
{
    public function view(): View
    {
        // Pastikan memanggil file remedialexcel di folder tahfidz
        return view('ujian.tahfidz.remedialexcel', [
            'remedialData' => Remedial::with('mahasiswi')->get()
        ]);
    }
}