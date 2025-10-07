<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{

    public function importExcel(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls'
    ]);

    Excel::import(new UsersImport, $request->file('file'));

    return redirect()->route('user')->with('success', 'Data user berhasil diimport!');
}

    public function index(){
        $data = array(
            'title'         => 'Data User',
            'menuAdminUser' => 'active',
            'user'          => User::get(),
        );
        return view('admin/user/index',$data);
    }

    public function create(){
        $data = array(
            'title'         => 'Tambah Data User',
            'menuAdminUser' => 'active',
            
        );
        return view('admin/user/create',$data);
    }


    public function store(Request $request){
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|unique:users,email',
            'password' => 'required|confirmed|min:8'
        ],[
            'name.required'     => 'Nama Tidak Boleh Kosong',
            'email.required'    => 'Email Tidak Boleh Kosong',
            'email.unique'      => 'Email Sudah Digunakan',
            'password.required' => 'Password Tidak Boleh Kosong',
            'password.confirmed' => 'Password Tidak Sama',
            'password.min' => 'Password minimal 8 karakter',
        ]);

        $user = new User;
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->password     = Hash::make($request->password);
        $user->save();

        return redirect()->route('user')->with('success','Data berhasil ditambahkan');
    }

     public function update(Request $request, $id){
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|unique:users,email,'.$id,
            'password' => 'nullable|confirmed|min:8',
        ],[
            'name.required'     => 'Nama Tidak Boleh Kosong',
            'email.required'    => 'Email Tidak Boleh Kosong',
            'email.unique'      => 'Email Sudah Digunakan',
            
            'password.confirmed' => 'Password Tidak Sama',
            'password.min' => 'Password minimal 8 karakter',
        ]);

        $user = User::findOrFail($id);
        $user->name         = $request->name;
        $user->email        = $request->email;
        if($request->filled('password')){
            
        $user->password     = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('user')->with('success','Data berhasil diedit');
    }


    public function edit($id){
        $data = array(
            'title'         => 'Edit Data User',
            'menuAdminUser' => 'active',
            'user'          => User::findOrFail($id),
            
        );
        return view('admin/user/edit',$data);
    }


    public function destroy($id){
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user')->with('success','Data berhasil dihapus');
    }

    public function excel(){
        $filename = now()->format('d-m-Y H.i.s');

        return Excel::download(new UsersExport, 'DataUser_'.$filename.'.xlsx');
    }

     public function pdf()
{
    $filename = now()->format('d-m-Y_H.i.s');

    // Ambil semua data user
    $users = User::all();

    // Kirim data ke view
    $pdf = Pdf::loadView('admin.user.pdf', compact('users'));

    // Set ukuran kertas & arah (landscape)
    return $pdf->setPaper('a4', 'portrait')->stream('DataUser_' . $filename . '.pdf');
}


    
}
