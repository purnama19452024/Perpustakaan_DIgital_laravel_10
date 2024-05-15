<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $jumlahBuku = Buku::count();
        $jumlahKategori = Kategori::count();
        $jumlahPeminjaman = Peminjaman::count();
        $jumlahUser = User::count();

        return view('template.template', compact('jumlahBuku', 'jumlahKategori', 'jumlahPeminjaman', 'jumlahUser'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('peminjaman.edituser', compact('user'));
    }




    public function create()
    {
        $userId = Auth::user()->id;
        return view('peminjaman.datausertambah', compact('userId'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'namalengkap' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'jeniskelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk avatar
            'level' => 'required|in:Petugas,Peminjam',
        ]);

        // Penanganan Avatar Default
        $avatarName = 'default.jpg'; // Avatar default

        if ($request->hasFile('avatar')) {
            $avatarName = $request->file('avatar')->getClientOriginalName();
            $request->file('avatar')->storeAs('avatars', $avatarName, 'public');
        }

        // Jika validasi sukses, lanjutkan dengan menyimpan data ke dalam database
        $user = User::create([
            'name' => $request->name,
            'namalengkap' => $request->namalengkap,
            'email' => $request->email,
            'jeniskelamin' => $request->jeniskelamin,
            'alamat' => $request->alamat,
            'avatar' => $avatarName,
            'password' => Hash::make($request->password),
            'level' => $request->level,
        ]);

        // Redirect ke halaman datauser
        return redirect()->route('datauser')->with('success', 'User berhasil ditambahkan sebagai Petugas');
    }




    public function update(Request $request, string $id)
    {
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

        return redirect()->route('datauser')->with('success', 'Data berhasil diperbarui');

    }



    public function destroy($id)
    {
        $user = User::findOrFail($id); // Menggunakan findOrFail agar akan menghasilkan 404 jika tidak ditemukan
        $user->delete();

        return back()->with('success', 'Data Berhasil Dihapus');
    }

}