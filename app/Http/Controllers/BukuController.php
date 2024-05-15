<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $DataBuku = Buku::with('category')->get();
        // dd($DataBuku { 0}->category);
        return view('buku.buku', compact('DataBuku'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Kategori::pluck('namakategori', 'id');
        return view('buku.tambahbuku', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string',
            'penerbit' => 'required|string',
            'tahunterbit' => 'required|integer',
            // 'bukuuplode' => 'required|string',
            // 'kategori_id' => 'required|exists:kategori,id', // Sesuaikan nama tabel dan kolomnya
        ]);

        Buku::create($request->all());
        // toastr()->success('Berhasil Menambahkan Data');

        return redirect('buku')->with('success', 'Data Berhasil Di Tambah');
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
        $pur = Buku::findorfail($id);
        $categories = Kategori::get();
        return view('buku.bukuedit', compact('pur', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string',
            'penerbit' => 'required|string',
            'tahunterbit' => 'required|integer',
            'bukuuplode' => 'required|string',
        ]);

        $buku = Buku::findOrFail($id);

        $buku->judul = $request->judul;
        $buku->penulis = $request->penulis;
        $buku->penerbit = $request->penerbit;
        $buku->tahunterbit = $request->tahunterbit;
        $buku->kategori_id = $request->kategori_id;
        $buku->bukuuplode = $request->bukuuplode;

        // Update the updated_at field to match the return date
        if ($buku->statuspeminjaman == '0') {
            // Ubah tanggal pengembalian buku ke tanggal saat ini
            $buku->tanggalpengembalian = now()->toDateString();

            // Atur status peminjaman kembali ke "Dipinjam"
            $buku->statuspeminjaman = '1';
        }

        $buku->save();

        return redirect('buku')->with('success', 'Data Berhasil Di Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $buku = Buku::findOrFail($request->id);

        // Memanggil event deleting untuk logging aktivitas
        $buku->delete();

        return back()->with('success', 'Data Berhasil Dihapus');
    }
}
