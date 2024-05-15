<?php

namespace App\Http\Controllers;

use App\Models\Informasi;
use Illuminate\Http\Request;

class InformasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $DataInformasi = Informasi::all();
        return view('informasi.informasi', compact('DataInformasi'));
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
        $put = Informasi::findorfail($id);
        return view('informasi.informasiedit', compact('put'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'namaperpustakaan' => 'required|string|max:255',
            'email' => 'required|string',
            'nomortlp' => 'required|integer',
            'alamat' => 'required|string',
            'provinsi' => 'required|string',
        ]);

        $informasi = Informasi::findOrFail($id);

        $informasi->namaperpustakaan = $request->namaperpustakaan;
        $informasi->email = $request->email;
        $informasi->nomortlp = $request->nomortlp;
        $informasi->alamat = $request->alamat;
        $informasi->provinsi = $request->provinsi;

        $informasi->save();

        return redirect('/informasi/edit/1')->with('success', 'Data Berhasil Di Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
