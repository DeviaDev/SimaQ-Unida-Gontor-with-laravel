<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Tempat;
use App\Models\Pengurus;
use App\Models\Mahasiswi;
use App\Models\KelompokLT;
use App\Models\Muhafidzoh;
use App\Imports\DosenImport;
use Illuminate\Http\Request;
use App\Imports\PengurusImport;
use Barryvdh\DomPDF\Facade\Pdf;
use Dflydev\DotAccessData\Data;
use App\Imports\MahasiswiImport;
use App\Exports\MuhafidzohExport;
use App\Imports\MuhafidzohImport;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class DataController extends Controller
{

// ================================================ DOSEN ==========================================================

    public function dosen(){
        $data = array(
            'title'         => ' Data Dosen Pembimbing',
            "menuData"  =>  "active",
            "menuDosen"  =>  "active",
            'data'          => Dosen::all(),
            'dosen' => Dosen::with([ 'kelompok', 'muhafidzoh','tempat'])->get(),
        );
        return view('data/dosen/dosen',$data);
    }



    public function create4(){
        $muhafidzohSudahDipakai = \App\Models\Dosen::pluck('id_muhafidzoh')->toArray();
        $data = array(
            'title'         => 'Tambah Data Dosen',
            'menuData' => 'active',
            'muhafidzoh'         => Muhafidzoh::whereNotIn('id_muhafidzoh', $muhafidzohSudahDipakai)->get(),
            'kelompok'         => KelompokLT::get(),
            'tempat'         => Tempat::get(),
            
        );
        return view('data/dosen/create',$data);
    }



public function store4(Request $request)
{
    $request->validate([
        'nama_dosen'    => 'required|string|max:255',
        'id_muhafidzoh' => 'required',
        'id_kelompok'   => 'required',
        'id_tempat'     => 'required',
    ], [
        'nama_dosen.required'    => 'Nama Tidak Boleh Kosong',
        'id_muhafidzoh.required' => 'Muhafidzoh Tidak Boleh Kosong',
        'id_kelompok.required'   => 'Kelompok Tidak Boleh Kosong',
        'id_tempat.required'     => 'Tempat Tidak Boleh Kosong',

    ]);

    //Simpan data
    $dosen = new dosen();
    $dosen->nama_dosen  = $request->nama_dosen;
    $dosen->id_muhafidzoh = $request->id_muhafidzoh;
    $dosen->id_kelompok  = $request->id_kelompok;
    $dosen->id_tempat = $request->id_tempat;
    $dosen->save();

    return redirect()->route('dosen')->with('success', 'Data berhasil ditambahkan');
}

  

     public function update4(Request $request, $id_dosen)
{
    $request->validate([
        'nama_dosen'    => 'required|string|max:255',
        'id_muhafidzoh' => 'required',
        'id_kelompok'   => 'required',
        'id_tempat'     => 'required',
    ], [
        'nama_dosen.required'    => 'Nama Tidak Boleh Kosong',
        'id_muhafidzoh.required' => 'Muhafidzoh Tidak Boleh Kosong',
        'id_kelompok.required'   => 'Kelompok Tidak Boleh Kosong',
        'id_tempat.required'     => 'Tempat Tidak Boleh Kosong',

    ]);


    $dosen = Pengurus::findOrFail($id_dosen);

   //Simpan data
    $dosen = new dosen();
    $dosen->nama_dosen  = $request->nama_dosen;
    $dosen->id_muhafidzoh = $request->id_muhafidzoh;
    $dosen->id_kelompok  = $request->id_kelompok;
    $dosen->id_tempat = $request->id_tempat;
    $dosen->save();

    return redirect()->route('dosen')->with('success', 'Data berhasil diedit');
}



    public function edit4($id_dosen){
        $data = array(
            'title'         => 'Edit Data Dosen',
            'menuData'      => 'active',
            'dosen'      => dosen::findOrFail($id_dosen),
            'muhafidzoh' => muhafidzoh::get(),
            'kelompok' => KelompokLT::get(),
            'tempat' => tempat::get(),
        );
        return view('data/dosen/edit',$data);
    }



    public function destroy4($id_dosen)
{
    $dosen = dosen::findOrFail($id_dosen);

    // Hapus data di database
    $dosen->delete();

    return redirect()->route('dosen')->with('success', 'Data berhasil dihapus');
}


    public function excel4(Request $request)
{
    $filename = now()->format('d-m-Y_H.i.s');
    $search = $request->input('search');

    $query = dosen::query();
    if (!empty($search)) {
        $query->where('nama', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%");
    }

    $dosen = $query->get();

    return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\DosenExport($dosen), 'DataDosen_'.$filename.'.xlsx');
}



public function importExcel4(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls|max:2048',
    ], [
        'file.required' => 'File Excel wajib diupload.',
        'file.mimes' => 'File harus berformat .xlsx atau .xls.',
        'file.max' => 'Ukuran file terlalu besar (maksimal 2MB).',
    ]);

    try {
        Excel::import(new DosenImport, $request->file('file'));
        return redirect()->route('dosen')->with('success', 'Data Pengurus berhasil diimport!');
    } catch (\Exception $e) {
        return redirect()->route('dosen')->with('error', '❌ Terjadi kesalahan saat import: ' . $e->getMessage());
    }
}


     public function pdf4(Request $request)
{
    $filename = now()->format('d-m-Y_H.i.s');
    $search = $request->input('search');

    // 🔍 Filter pencarian dari input search
    $query = dosen::query();
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    $dosen = $query->get();

    // 🖨️ Generate PDF
    $pdf = Pdf::loadView('data.dosen.pdf', compact('dosen'))
        ->setPaper('a4', 'portrait');

    return $pdf->stream('DataDosen_' . $filename . '.pdf');
}

public function getTempat($id_kelompok)
{
    $kelompok = \App\Models\KelompokLT::with('tempat')->find($id_kelompok);

    if ($kelompok && $kelompok->tempat) {
        return response()->json([
            'id_tempat' => $kelompok->tempat->id_tempat,
            'nama_tempat' => $kelompok->tempat->nama_tempat
        ]);
    }

    return response()->json(['error' => 'Tempat tidak ditemukan'], 404);
}


// ================================================ MAHASIWI ==========================================================

public function mahasiswi(){
        $data = array(
            'title'         => ' Data Mahasiswi',
            "menuData"  =>  "active",
            "menuMahasiswi"  =>  "active",
            'data'          => Mahasiswi::all(),
            'mahasiswi' => Mahasiswi::with(['dosen', 'muhafidzoh', 'kelompok', 'tempat'])->get(),
        );
        return view('data.mahasiswi.mahasiswi',$data);
    }



    public function create3(){
        $data = array(
            'title'         => 'Tambah Data Mahasiswi',
            'menuData' => 'active',
            'dosen'         => Dosen::get(),
            'muhafidzoh'         => Muhafidzoh::get(),
            'kelompok'         => KelompokLT::get(),
            'tempat'         => Tempat::get(),
            
        );
        return view('data/mahasiswi/create',$data);
    }



public function store3(Request $request)
{
    $request->validate([
        'nama_mahasiswi'    => 'required|string|max:255',
        'prodi'=> 'required',
        'semester' => 'required',
        'id_dosen' => 'required',
        'id_muhafidzoh' => 'required',
        'id_kelompok'   => 'required',
        'id_tempat'     => 'required',
    ], [
        'nama_dosen.required'    => 'Nama Tidak Boleh Kosong',
        'prodi.required'    => 'Program Studi Tidak Boleh Kosong',
        'semester.required'    => 'Semester Tidak Boleh Kosong',
        'id_dosen.required' => 'Dosen Tidak Boleh Kosong',
        'id_muhafidzoh.required' => 'Muhafidzoh Tidak Boleh Kosong',
        'id_kelompok.required'   => 'Kelompok Tidak Boleh Kosong',
        'id_tempat.required'     => 'Tempat Tidak Boleh Kosong',

    ]);

    //Simpan data
    $mahasiswi = new mahasiswi();
    $mahasiswi->nama_mahasiswi  = $request->nama_mahasiswi;
    $mahasiswi->prodi  = $request->prodi;
    $mahasiswi->semester  = $request->semester;
    $mahasiswi->id_dosen = $request->id_dosen;
    $mahasiswi->id_muhafidzoh = $request->id_muhafidzoh;
    $mahasiswi->id_kelompok  = $request->id_kelompok;
    $mahasiswi->id_tempat = $request->id_tempat;
    $mahasiswi->save();

    return redirect()->route('mahasiswi')->with('success', 'Data berhasil ditambahkan');
}

  

     public function update3(Request $request, $id_mahasiswi)
{
    $request->validate([
        'foto'  => 'nullable|image|max:2048',
        'nama'  => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:pengurus,email,' . $id_mahasiswi,
    ], [
        'foto.image'      => 'File foto harus berupa gambar',
        'nama.required'   => 'Nama Tidak Boleh Kosong',
        'email.required'  => 'Email Tidak Boleh Kosong',
        'email.unique'    => 'Email Sudah Digunakan',
    ]);

    $mahasiswi = mahasiswi::findOrFail($id_mahasiswi);

    // update field lain
    $mahasiswi->nama  = $request->nama;
    $mahasiswi->email = $request->email;
    $mahasiswi->save();

    return redirect()->route('mahasiswi')->with('success', 'Data berhasil diedit');
}



    public function edit3($id_mahasiswi){
        $data = array(
            'title'         => 'Edit Data Mahasiswi',
            'menuData'      => 'active',
            'mahasiswi'      => mahasiswi::findOrFail($id_mahasiswi),
            
        );
        return view('data/mahasiswi/edit',$data);
    }



    public function destroy3($id_mahasiswi)
{
    $mahasiswi = Pengurus::findOrFail($id_mahasiswi);


    // Hapus data di database
    $mahasiswi->delete();

    return redirect()->route('mahasiswi')->with('success', 'Data berhasil dihapus');
}



    public function excel3(Request $request)
{
    $filename = now()->format('d-m-Y_H.i.s');
    $search = $request->input('search');

    $query = mahasiswi::query();
    if (!empty($search)) {
        $query->where('nama', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%");
    }

    $mahasiswi = $query->get();

    return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\MahasiswiExport($mahasiswi), 'DataMahasiswi_'.$filename.'.xlsx');
}



public function importExcel3(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls|max:2048',
    ], [
        'file.required' => 'File Excel wajib diupload.',
        'file.mimes' => 'File harus berformat .xlsx atau .xls.',
        'file.max' => 'Ukuran file terlalu besar (maksimal 2MB).',
    ]);

    try {
        Excel::import(new MahasiswiImport, $request->file('file'));
        return redirect()->route('mahasiswi')->with('success', 'Data Pengurus berhasil diimport!');
    } catch (\Exception $e) {
        return redirect()->route('mahasiswi')->with('error', '❌ Terjadi kesalahan saat import: ' . $e->getMessage());
    }
}



     public function pdf3(Request $request)
{
    $filename = now()->format('d-m-Y_H.i.s');
    $search = $request->input('search');

    // 🔍 Filter pencarian dari input search
    $query = mahasiswi::query();
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    $mahasiswi = $query->get();

    // 🖨️ Generate PDF
    $pdf = Pdf::loadView('data.mahasiswi.pdf', compact('mahasiswi'))
        ->setPaper('a4', 'portrait');

    return $pdf->stream('DataMahasiswi_' . $filename . '.pdf');
}


// ================================================ MUHAFIDZOH ==========================================================

    public function muhafidzoh(){
        $data = array(
            'title'         => ' Data Muhafidzoh',
            "menuData"  =>  "active",
            "menuMuhafidzoh"  =>  "active",
            'data'          => Muhafidzoh::all(),
            'Muhafidzoh' => Muhafidzoh::with([ 'kelompok', 'tempat'])->get(),
        );
        return view('data/muhafidzoh/muhafidzoh',$data);
    }



        public function create2(){
        $data = array(
            'title'         => 'Tambah Data Muhafidzoh',
            'menuData' => 'active',
            'kelompok'         => KelompokLT::get(),
            'tempat'         => Tempat::get(),

            
        );
        return view('data/muhafidzoh/create',$data);
    }



public function store2(Request $request)
{
    $request->validate([
        'nama_muhafidzoh'    => 'required|string|max:255',
        'keterangan'    => 'required|string|max:255',
        'id_kelompok'   => 'required',
        'id_tempat'     => 'required',
    ], [
        'nama_muhafidzoh.required'    => 'Muhafidzoh Tidak Boleh Kosong',
        'keterangan.required'    => 'Keterangan Tidak Boleh Kosong',
        'id_kelompok.required'   => 'Kelompok Tidak Boleh Kosong',
        'id_tempat.required'     => 'Tempat Tidak Boleh Kosong',

    ]);


   //Simpan data
    $muhafidzoh = new muhafidzoh();
    $muhafidzoh->nama_muhafidzoh  = $request->nama_muhafidzoh;
    $muhafidzoh->keterangan = $request->keterangan;
    $muhafidzoh->id_kelompok  = $request->id_kelompok;
    $muhafidzoh->id_tempat = $request->id_tempat;
    $muhafidzoh->save();

    return redirect()->route('muhafidzoh')->with('success', 'Data berhasil diedit');
}

  

     public function update2(Request $request, $id_muhafidzoh)
{
    $request->validate([
        'foto'  => 'nullable|image|max:2048',
        'nama'  => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:pengurus,email,' . $id_muhafidzoh,
    ], [
        'foto.image'      => 'File foto harus berupa gambar',
        'nama.required'   => 'Nama Tidak Boleh Kosong',
        'email.required'  => 'Email Tidak Boleh Kosong',
        'email.unique'    => 'Email Sudah Digunakan',
    ]);

    $muhafidzoh = Pengurus::findOrFail($id_muhafidzoh);


    // update field lain
    $muhafidzoh->nama  = $request->nama;
    $muhafidzoh->email = $request->email;
    $muhafidzoh->save();

    return redirect()->route('muhafidzoh')->with('success', 'Data berhasil diedit');
}



    public function edit2($id_muhafidzoh){
        $data = array(
            'title'         => 'Edit Data Muhafidzoh',
            'menuData'      => 'active',
            'muhafidzoh'      => muhafidzoh::findOrFail($id_muhafidzoh),
            
        );
        return view('data/muhafidzoh/edit',$data);
    }



    public function destroy2($id_muhafidzoh)
{
    $muhafidzoh = Pengurus::findOrFail($id_muhafidzoh);


    // Hapus data di database
    $muhafidzoh->delete();

    return redirect()->route('muhafidzoh')->with('success', 'Data berhasil dihapus');
}



    public function excel2(Request $request)
{
    $filename = now()->format('d-m-Y_H.i.s');
    $search = $request->input('search');

    $query = muhafidzoh::query();
    if (!empty($search)) {
        $query->where('nama', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%");
    }

    $muhafidzoh = $query->get();

    return \Maatwebsite\Excel\Facades\Excel::download(new MuhafidzohExport($muhafidzoh), 'DataMuhafidzoh_'.$filename.'.xlsx');
}



public function importExcel2(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls|max:2048',
    ], [
        'file.required' => 'File Excel wajib diupload.',
        'file.mimes' => 'File harus berformat .xlsx atau .xls.',
        'file.max' => 'Ukuran file terlalu besar (maksimal 2MB).',
    ]);

    try {
        Excel::import(new MuhafidzohImport, $request->file('file'));
        return redirect()->route('muhafidzoh')->with('success', 'Data Pengurus berhasil diimport!');
    } catch (\Exception $e) {
        return redirect()->route('muhafidzoh')->with('error', '❌ Terjadi kesalahan saat import: ' . $e->getMessage());
    }
}


     public function pdf2(Request $request)
{
    $filename = now()->format('d-m-Y_H.i.s');
    $search = $request->input('search');

    // 🔍 Filter pencarian dari input search
    $query = muhafidzoh::query();
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    $muhafidzoh = $query->get();

    // 🖨️ Generate PDF
    $pdf = Pdf::loadView('data.muhafidzoh.pdf', compact('muhafidzoh'))
        ->setPaper('a4', 'portrait');

    return $pdf->stream('DataMuhafidzoh_' . $filename . '.pdf');
}

// ================================================ PENGURUS ==========================================================
    public function pengurus(){
        $data = array(
            'title'         => ' Data Pengurus',
            "menuData"      =>  "active",
            "menuPengurus"  =>  "active",
            'data'          => Pengurus::all(),
        );
        return view('data.pengurus.pengurus', $data);

    }



    public function create1(){
        $data = array(
            'title'         => 'Tambah Data Pengurus',
            'menuData' => 'active',
            
        );
        return view('data/pengurus/create',$data);
    }


public function store1(Request $request)
{
    $request->validate([
        'foto'  => 'required|image|max:2048',
        'nama'  => 'required|string|max:255',
        'email' => 'required|unique:pengurus,email|max:255',
    ], [
        'foto.required' => 'Foto Tidak Boleh Kosong',
        'nama.required' => 'Nama Tidak Boleh Kosong',
        'email.required'    => 'Email Tidak Boleh Kosong',
        'email.unique'  => 'Email Sudah Digunakan',

    ]);

    // Upload foto ke storage/app/public/foto_pengurus
    $namaFile = null;
    if ($request->hasFile('foto')) {
        $foto = $request->file('foto');
        $namaFile = time() . '_' . $foto->getClientOriginalName();

        // Gunakan disk 'public' biar pasti ke storage/app/public
        Storage::disk('public')->putFileAs('foto_pengurus', $foto, $namaFile);
    }

    // Simpan data
    $pengurus = new Pengurus();
    $pengurus->foto  = $namaFile;
    $pengurus->nama  = $request->nama;
    $pengurus->email = $request->email;
    $pengurus->save();

    return redirect()->route('pengurus')->with('success', 'Data berhasil ditambahkan');
}

  

     public function update1(Request $request, $id)
{
    $request->validate([
        'foto'  => 'nullable|image|max:2048',
        'nama'  => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:pengurus,email,' . $id,
    ], [
        'foto.image'      => 'File foto harus berupa gambar',
        'nama.required'   => 'Nama Tidak Boleh Kosong',
        'email.required'  => 'Email Tidak Boleh Kosong',
        'email.unique'    => 'Email Sudah Digunakan',
    ]);

    $pengurus = Pengurus::findOrFail($id);

    // kalau upload foto baru
    if ($request->hasFile('foto')) {
        // hapus foto lama
        if ($pengurus->foto && Storage::disk('public')->exists('foto_pengurus/' . $pengurus->foto)) {
            Storage::disk('public')->delete('foto_pengurus/' . $pengurus->foto);
        }

        // simpan foto baru
        $foto = $request->file('foto');
        $namaFile = time() . '_' . $foto->getClientOriginalName();
        Storage::disk('public')->putFileAs('foto_pengurus', $foto, $namaFile);

        $pengurus->foto = $namaFile;
    }

    // update field lain
    $pengurus->nama  = $request->nama;
    $pengurus->email = $request->email;
    $pengurus->save();

    return redirect()->route('pengurus')->with('success', 'Data berhasil diedit');
}



    public function edit1($id){
        $data = array(
            'title'         => 'Edit Data Pengurus',
            'menuData'      => 'active',
            'Pengurus'      => pengurus::findOrFail($id),
            
        );
        return view('data/pengurus/edit',$data);
    }



    public function destroy1($id)
{
    $pengurus = Pengurus::findOrFail($id);


    // Hapus foto kalau ada
    if ($pengurus->foto && Storage::disk('public')->exists('foto_pengurus/' . $pengurus->foto)) {
        Storage::disk('public')->delete('foto_pengurus/' . $pengurus->foto);
    }

    // Hapus data di database
    $pengurus->delete();

    return redirect()->route('pengurus')->with('success', 'Data berhasil dihapus');
}


    public function excel1(Request $request)
{
    $filename = now()->format('d-m-Y_H.i.s');
    $search = $request->input('search');

    $query = Pengurus::query();
    if (!empty($search)) {
        $query->where('nama', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%");
    }

    $Pengurus = $query->get();

    return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PengurusExport($Pengurus), 'DataPengurus_'.$filename.'.xlsx');
}



public function importExcel1(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls|max:2048',
    ], [
        'file.required' => 'File Excel wajib diupload.',
        'file.mimes' => 'File harus berformat .xlsx atau .xls.',
        'file.max' => 'Ukuran file terlalu besar (maksimal 2MB).',
    ]);

    try {
        Excel::import(new PengurusImport, $request->file('file'));
        return redirect()->route('pengurus')->with('success', 'Data Pengurus berhasil diimport!');
    } catch (\Exception $e) {
        return redirect()->route('pengurus')->with('error', '❌ Terjadi kesalahan saat import: ' . $e->getMessage());
    }
}


     public function pdf1(Request $request)
{
    $filename = now()->format('d-m-Y_H.i.s');
    $search = $request->input('search');

    // 🔍 Filter pencarian dari input search
    $query = Pengurus::query();
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    $Pengurus = $query->get();

    // 🖨️ Generate PDF
    $pdf = Pdf::loadView('data.pengurus.pdf', compact('Pengurus'))
        ->setPaper('a4', 'portrait');

    return $pdf->stream('DataPengurus_' . $filename . '.pdf');
}


}
