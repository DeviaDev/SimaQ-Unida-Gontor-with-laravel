<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UsersExport implements FromView
{
    public function view(): View
    {
        $data = array(
            'user'=> User::get(),
        );
        return view('admin/user/excel',$data);
    }
}
