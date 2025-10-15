<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MuhafidzohExport implements FromView
{
    protected $muhafidzoh;

    public function __construct($muhafidzoh)
    {
        $this->muhafidzoh = $muhafidzoh;
    }

    public function view(): View
    {
        return view('data.muhafidzoh.excel', [
            'muhafidzoh' => $this->muhafidzoh
        ]);
    }
}
