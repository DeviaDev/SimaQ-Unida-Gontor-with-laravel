<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PengurusExport implements FromView
{
    protected $Pengurus;

    public function __construct($Pengurus)
    {
        $this->Pengurus = $Pengurus;
    }

    public function view(): View
    {
        return view('data.pengurus.excel', [
            'Pengurus' => $this->Pengurus
        ]);
    }
}
