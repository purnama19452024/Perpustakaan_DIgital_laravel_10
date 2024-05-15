<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $DataPeminjaman = Peminjaman::all(); // Sesuaikan dengan cara Anda menyimpan data peminjaman

        // Kirim data peminjaman ke view
        $jumlahBuku = Buku::count();
        $jumlahKategori = Kategori::count();
        $jumlahPeminjaman = Peminjaman::count();
        $jumlahUser = User::count();
        $jumlahPetugas = User::count();

        $jumlahUser = User::where('level', 'Peminjam')->count();
        $jumlahPetugas = User::where('level', 'Petugas')->count();

        return view('peminjaman.peminjaman', compact('jumlahBuku', 'jumlahKategori', 'jumlahPeminjaman', 'jumlahUser', 'jumlahPetugas'));

    }

    public function utama()
    {
        if (Auth::user()->level == 'Admin' || Auth::user()->level == 'Petugas') {
            $DataPeminjaman = Peminjaman::with('buku')->get(); // Memuat data peminjaman beserta buku yang terkait
        } elseif (Auth::user()->level == 'Peminjam') {
            $DataPeminjaman = Peminjaman::where('user_id', Auth::user()->id)->with('buku')->get(); // Memuat data peminjaman beserta buku yang terkait
        }
        return view('peminjaman.index', compact('DataPeminjaman'));
    }

    public function datauser()
    {
        $DataUser = User::where('level', 'Peminjam')->get();
        return view('peminjaman.datauser', compact('DataUser'));
    }
    public function daftartampilan()
    {
        $categories = Buku::get();
        // dd($categories);
        return view('peminjaman.daftartampilan', compact('categories'));
    }

    public function LaporanPeminjam()
    {
        // return view('jurusan.jurusan');
        $dataCetakLaporan = Peminjaman::all();
        // dd($dataCetakLaporan);
        return view('peminjaman.laporanpeminjam', compact('dataCetakLaporan'));
    }
    public function LaporanBuku()
    {
        // return view('jurusan.jurusan');
        $LaporanBuku = Buku::with('category')->get();
        // dd($dataCetakLaporan);
        return view('peminjaman.laporanbuku', compact('LaporanBuku'));
    }
    public function LaporanUser()
    {
        // return view('jurusan.jurusan');
        $DataUser = User::where('level', 'Peminjam')->get();
        return view('peminjaman.laporanuser', compact('DataUser'));
    }

    public function cetakpdf()
    {
        $buku = Buku::with('category')->get();

        $pdf = PDF::loadview('laporan.laporanbuku', ['buku' => $buku]);
        return $pdf->stream('laporan_buku.pdf');
    }
    public function cetakpdfpeminjaman()
    {
        $peminjaman = Peminjaman::all();

        $pdf = PDF::loadview('laporan.laporanpeminjam', ['peminjam' => $peminjaman]);
        return $pdf->stream('laporan_peminjam.pdf');
    }
    public function cetakpdfUser()
    {
        $DataUser = User::where('level', 'Peminjam')->get();

        $pdf = PDF::loadview('laporan.laporanuser', ['DataUser' => $DataUser]);
        return $pdf->stream('laporan_user.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */


    public function create()
    {


        return view('peminjaman.tambah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $tanggalPeminjaman = $request->tanggal_pinjam;
            $tanggalPengembalian = $request->tanggal_kembali;

            Peminjaman::create([
                'nama' => $request->nama,
                'user_id' => $request->user_id,
                'buku_id' => $request->buku_id,
                'kategori_id' => $request->kategori_id,
                'tanggalpeminjaman' => $tanggalPeminjaman,
                'tanggalpengembalian' => $tanggalPengembalian,
            ]);

            DB::commit();

            return redirect()->route('peminjamutama')->with('success', 'Data peminjaman berhasil disimpan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data peminjaman.');
        }
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
    public function update($id)
    {
        // Find the Peminjaman record with the given ID
        $peminjaman = Peminjaman::find($id);

        if ($peminjaman) {
            // Set the status of the Peminjaman to "returned"
            $peminjaman->statuspeminjaman = Peminjaman::STATUS_DI_KEMBALIKAN;

            // Update the tanggalpengembalian to the current date and time
            $peminjaman->tanggalpengembalian = now();

            // Save the changes to the database
            $peminjaman->save();

            // Redirect to the intended route after successful update
            return redirect()->route('peminjamutama')->with('success', 'Buku berhasil dikembalikan.');
        } else {
            // Handle if the Peminjaman is not found
            return redirect()->route('peminjamanindex')->with('error', 'Peminjaman tidak ditemukan.');
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id); // Menggunakan findOrFail agar akan menghasilkan 404 jika tidak ditemukan
        $peminjaman->delete();

        return back()->with('success', 'Data Berhasil Dihapus');
    }
}
