<?php
namespace App\Exports;

use App\Models\Ujiantahsin;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class TahsinExport implements FromView
{
    public function view(): View
    {
        return view('ujian.tahsin.exceltahsin', [
            'tahsinData' => Ujiantahsin::with('mahasiswi')->get()
        ]);
    }
}