<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MahasiswiExport implements FromView
{
    protected $mahasiswi;

    public function __construct($mahasiswi)
    {
        $this->mahasiswi = $mahasiswi;
    }

    public function view(): View
    {
        return view('data.mahasiswi.excel', [
            'mahasiswi' => $this->mahasiswi
        ]);
    }
}
