<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswi;
use App\Models\Ujianmandiri;
use Illuminate\Http\Request;
use App\Exports\MandiriExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;


class UjianController extends Controller
{
    // ================================================ UJIAN MANDIRI ==========================================================

    public function mandiri()
    {
        $data = array(
            'title'       => 'Data Ujian Mandiri',
            'menuUjian'   => 'active',
            'menuData'    => 'active', // Menyamakan konsep menuData active
            'mandiri' => Ujianmandiri::with('mahasiswi')->get(),
        );

        return view('ujian.tahfidz.mandiri', $data);
    }

    public function create5()
    {
        $data = array(
            'title'     => 'Tambah Data Ujian Mandiri',
            'menuUjian' => 'active',
            'mahasiswi' => Mahasiswi::all(),
        );

        return view('ujian.tahfidz.createmandiri', $data);
    }

    public function store5(Request $request)
    {
        $request->validate([
            'id_mahasiswi'     => 'required',
            'materi'           => 'required|string',
            'keterangan_ujian' => 'required|in:Mandiri,Serentak',
            'nilai'            => 'required|in:a,a-,b+,b,b-,c+,c,c-',
        ], [
            'id_mahasiswi.required'     => 'Mahasiswi Tidak Boleh Kosong',
            'materi.required'           => 'Materi Tidak Boleh Kosong',
            'keterangan_ujian.required' => 'Keterangan Ujian Tidak Boleh Kosong',
            'nilai.required'            => 'Nilai Tidak Boleh Kosong',
        ]);

        // Simpan data menggunakan model yang konsisten
        $mandiri = new Ujianmandiri();
        $mandiri->id_mahasiswi     = $request->id_mahasiswi;
        $mandiri->materi           = $request->materi;
        $mandiri->keterangan_ujian = $request->keterangan_ujian;
        $mandiri->nilai            = $request->nilai;
        $mandiri->save();

        return redirect()->route('mandiri')->with('success', 'Data Ujian Berhasil Ditambah!');
    }

    public function edit5($id_ujian_mandiri) // Sesuaikan nama variabel agar enak dibaca
{
    $data = array(
        'title'     => 'Edit Data Ujian Mandiri',
        'menuUjian' => 'active',
        'ujian'     => Ujianmandiri::with('mahasiswi')->findOrFail($id_ujian_mandiri),
        'mahasiswi' => Mahasiswi::get(),
    );

    return view('ujian.tahfidz.edit', $data);
}

    public function update5(Request $request, $id_ujian_mandiri)
    {
        $request->validate([
            'id_mahasiswi'     => 'required',
            'materi'           => 'required|string',
            'keterangan_ujian' => 'required|in:Mandiri,Serentak',
            'nilai'            => 'required|in:a,a-,b+,b,b-,c+,c,c-',
        ]);

        $mandiri = Ujianmandiri::findOrFail($id_ujian_mandiri);
        $mandiri->id_mahasiswi     = $request->id_mahasiswi;
        $mandiri->materi           = $request->materi;
        $mandiri->keterangan_ujian = $request->keterangan_ujian;
        $mandiri->nilai            = $request->nilai;
        $mandiri->save();

        return redirect()->route('mandiri')->with('success', 'Data berhasil diedit');
    }

    public function destroy5($id_ujian_mandiri)
    {
        $mandiri = Ujianmandiri::findOrFail($id_ujian_mandiri);
        $mandiri->delete();

        return redirect()->route('mandiri')->with('success', 'Data berhasil dihapus');
    }

    public function excel5(Request $request)
    {
        $filename = now()->format('d-m-Y_H.i.s');
        $search = $request->input('search');

        $query = Ujianmandiri::with(['mahasiswi']);

        if (!empty($search)) {
            $query->whereHas('mahasiswi', function ($q) use ($search) {
                $q->where('nama_mahasiswi', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            })->orWhere('materi', 'like', "%{$search}%");
        }

        $mandiri = $query->get();

        return Excel::download(new MandiriExport($mandiri), 'DataUjian.xlsx');
    }

    public function pdf5(Request $request)
    {
        $filename = now()->format('d-m-Y_H.i.s');
        $search = $request->input('search');

        $query = Ujianmandiri::with(['mahasiswi']);

        if (!empty($search)) {
            $query->whereHas('mahasiswi', function ($q) use ($search) {
                $q->where('nama_mahasiswi', 'like', "%{$search}%");
            })->orWhere('materi', 'like', "%{$search}%");
        }

        $data = $query->get();
        $pdf = Pdf::loadView('ujian.tahfidz.pdf', ['mandiriData' => $data])
            ->setPaper('a4', 'potrait');
        return $pdf->stream('DataUjian.pdf');

        $data = $query->get(); 
    
    }
}