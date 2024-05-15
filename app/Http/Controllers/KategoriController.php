<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $DataKategori = Kategori::all();
        return view('kategori.kategori', compact('DataKategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kategori.kategoritambah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'namakategori' => 'required|string|max:255',
            // 'kategori_id' => 'required|exists:kategori,id', // Sesuaikan nama tabel dan kolomnya
        ]);

        Kategori::create([
            'namakategori' => $request->namakategori,
            // 'kategori_id' => $request->kategori_id, // Gunakan nama kolom yang benar
        ]);

        return redirect('kategori')->with('success', 'Data Berhasil Di Tambah');
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
        $kar = Kategori::findorfail($id);
        return view('kategori.kategoriedit', compact('kar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'namakategori' => 'required|string|max:255',
        ]);

        $kar = Kategori::findOrFail($id);

        $kar->namakategori = $request->namakategori;

        $kar->save();

        return redirect('kategori')->with('success', 'Data Berhasil Di Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $kategori = Kategori::findOrFail($request->id);

        // Pastikan tidak ada buku yang terkait dengan kategori yang akan dihapus
        if ($kategori->buku()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus kategori karena masih terdapat buku yang terkait.');
        }

        $kategori->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }

}
