<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UjianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }



    public function mandiri(){
        $data = array(
            'title'         => ' Data Ujian Mandiri',
            "menuUjian"  =>  "active",
            "menuUjianTahfidz"  =>  "active",
            "mandiri"  =>  "active",
        );
        return view('ujian/tahfidz/mandiri',$data);
    }


    public function serentak(){
        $data = array(
            'title'         => ' Data Ujian Serentak',
            "menuUjian"  =>  "active",
            "menuUjianTahfidz"  =>  "active",
            "serentak"  =>  "active",
        );
        return view('ujian/tahfidz/serentak',$data);
    }


    public function remedial(){
        $data = array(
            'title'         => ' Data Remedial',
            "menuUjian"  =>  "active",
            "menuUjianTahfidz"  =>  "active",
            "remedial"  =>  "active",
        );
        return view('ujian/tahfidz/remedial',$data);
    }



    public function tahsin(){
        $data = array(
            'title'         => ' Data Ujian Tahsin',
            "menuUjian"  =>  "active",
            "menuUjianTahsin"  =>  "active",
            "tahsin"  =>  "active",
        );
        return view('ujian/tahsin/tahsin',$data);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
