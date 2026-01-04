<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MandiriExport implements FromView
{
    protected $mandiri;

    public function __construct($mandiri)
    {
        $this->mandiri = $mandiri;
    }

    public function view(): View
    {
        return view('ujian.tahfidz.excel', [
            'mandiriData' => $this->mandiri
        ]);
    }
}
