<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DosenExport implements FromView
{
    protected $dosen;

    public function __construct($dosen)
    {
        $this->dosen = $dosen;
    }

    public function view(): View
    {
        return view('data.dosen.excel', [
            'dosen' => $this->dosen
        ]);
    }
}
