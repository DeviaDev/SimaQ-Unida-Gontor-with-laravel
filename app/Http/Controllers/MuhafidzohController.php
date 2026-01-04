<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Muhafidzoh2;

class MuhafidzohController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Data Muhafidzoh';

        $gedung   = $request->gedung;
        $kelompok = $request->kelompok;

        // LIST GEDUNG
        $gedungList = Muhafidzoh2::select('gedung')
            ->distinct()
            ->orderBy('gedung')
            ->pluck('gedung');

        // LIST KELOMPOK BERDASARKAN GEDUNG
        $kelompokList = [];
        if ($gedung) {
            $kelompokList = Muhafidzoh2::where('gedung', $gedung)
                ->select('kelompok')
                ->distinct()
                ->orderBy('kelompok')
                ->pluck('kelompok');
        }

        // DATA MUHAFIDZOH
        $muhafidzoh = collect();
        if ($gedung && $kelompok) {
            $muhafidzoh = Muhafidzoh2::where('gedung', $gedung)
                ->where('kelompok', $kelompok)
                ->orderBy('nama')
                ->get();
        }

        return view('muhafidzoh.index', compact(
            'title',
            'gedungList',
            'kelompokList',
            'muhafidzoh'
        ));
    }
}
