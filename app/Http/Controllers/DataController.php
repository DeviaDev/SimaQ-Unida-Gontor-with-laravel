<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Pengurus;
use App\Models\Mahasiswi;
use App\Models\Muhafidzoh;
use Illuminate\Http\Request;
use App\Imports\PengurusImport;
use Barryvdh\DomPDF\Facade\Pdf;
use Dflydev\DotAccessData\Data;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class DataController extends Controller
{

    public function pengurus(){
        $data = array(
            'title'         => ' Data Pengurus',
            "menuData"      =>  "active",
            "menuPengurus"  =>  "active",
            'data'          => Pengurus::all(),
        );
        return view('data.pengurus.pengurus', $data);

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



    public function create1(){
        $data = array(
            'title'         => 'Tambah Data Pengurus',
            'menuAdminUser' => 'active',
            
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







    public function mahasiswi(){
        $data = array(
            'title'         => ' Data Mahasiswi',
            "menuData"  =>  "active",
            "menuMahasiswi"  =>  "active",
            'data'          => Mahasiswi::all(),
            'mahasiswi' => Mahasiswi::with(['dosen', 'muhafidzoh', 'kelompok', 'tempat'])->get(),
        );
        return view('data/mahasiswi/mahasiswi',$data);
    }



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

   
    public function create()
    {
        //
    }

        public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }



    
}
