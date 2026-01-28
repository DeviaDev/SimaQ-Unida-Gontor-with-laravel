<?php

namespace App\Exports;

use App\Models\Ujiantahsin;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TahsinExport implements FromView, ShouldAutoSize
{
    protected $role;

    // 1. CONSTRUCTOR: Menerima data role dari Controller
    public function __construct($role)
    {
        $this->role = $role;
    }

    // 2. QUERY & VIEW
    public function view(): View
    {
        // Filter query berdasarkan role yang diterima
        $query = Ujiantahsin::with(['mahasiswi', 'dosen']);

        if ($this->role) {
            $query->where('kategori', $this->role);
        }

        return view('ujian.tahsin.exceltahsin', [
            'tahsinData' => $query->get(),
            'role'       => $this->role
        ]);
    }
}