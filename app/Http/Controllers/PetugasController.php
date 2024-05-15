<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil data peminjaman
        $DataPeminjaman = Peminjaman::all(); // Sesuaikan dengan cara Anda menyimpan data peminjaman

        // Kirim data peminjaman ke view
        $jumlahBuku = Buku::count();
        $jumlahKategori = Kategori::count();
        $jumlahPeminjaman = Peminjaman::count();
        $jumlahUser = User::count();

        $jumlahUser = User::where('level', 'Peminjam')->count();

        return view('petugas.petugas', compact('jumlahBuku', 'jumlahKategori', 'jumlahPeminjaman', 'jumlahUser'));
    }
    public function utama()
    {
        // Mengambil hanya data petugas dengan level 'Petugas'
        $DataPetugas = User::where('level', 'Petugas')->get();

        return view('petugas.daftarpetugas', compact('DataPetugas'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $petugas = Auth::user()->id;
        return view('petugas.tambahpetugas', compact('petugas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'namalengkap' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'jeniskelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'level' => 'required|in:Petugas,Peminjam', // Tambahkan validasi untuk level
        ]);
        // dd($request);

        // Pastikan level disetel sebagai 'Petugas' jika itu yang dipilih
        $data = $request->all();
        if ($request->level == 'Petugas') {
            $data['level'] = 'Petugas';
        }

        // Jika validasi sukses, lanjutkan dengan menyimpan data ke dalam database
        User::create($data);

        return redirect()->route('petugasutama'); // Ganti dengan route yang sesuai
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
        // Ambil data petugas dari database berdasarkan $id
        $petugas = User::findOrFail($id);

        // Kirim data petugas ke tampilan edit
        return view('petugas.editpetugas', compact('petugas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request);
        $request->validate([
            'name' => 'required|string|max:255',
            'namalengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // max:2048 menunjukkan ukuran maksimum file 2MB
            'level' => 'required|string|max:255', // tambahkan validasi untuk level
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->namalengkap = $request->namalengkap;
        $user->email = $request->email;
        $user->level = $request->level; // tambahkan level pengguna

        if ($request->hasFile('avatar')) {
            // Jika avatar diunggah, pindahkan ke direktori yang ditentukan
            $avatar = $request->file('avatar');
            $nama_avatar = time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('avatar'), $nama_avatar);
            $user->avatar = $nama_avatar;
        }

        if ($request->filled('new_password')) {
            // Jika password baru diisi, hash password baru dan simpan
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('petugasutama')->with('success', 'Data berhasil diperbarui');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pur = User::findOrFail($id); // Menggunakan findOrFail agar akan menghasilkan 404 jika tidak ditemukan
        $pur->delete();

        return back()->with('success', 'Data Berhasil Dihapus');
    }
}