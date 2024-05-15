<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jumlahBuku = Buku::count();
        $jumlahKategori = Kategori::count();
        $jumlahPeminjaman = Peminjaman::count();
        $jumlahUser = User::count();
        $jumlahPetugas = User::count();

        $jumlahUser = User::where('level', 'Peminjam')->count();
        $jumlahPetugas = User::where('level', 'Petugas')->count();

        return view('template.template', compact('jumlahBuku', 'jumlahKategori', 'jumlahPeminjaman', 'jumlahUser', 'jumlahPetugas'));
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
