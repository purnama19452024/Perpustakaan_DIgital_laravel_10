<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DatadiriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user()->id;
        return view('peminjaman.datadiri', compact('user'));
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
        $request->validate([
            'name' => 'required|string|max:255',
            'namalengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::user()->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // max:2048 menunjukkan ukuran maksimum file 2MB
        ]);

        // Periksa apakah ada file avatar yang diunggah
        if ($request->hasFile('avatar')) {
            $poster = $request->file('avatar');

            // Pindahkan file avatar yang diunggah ke direktori yang ditentukan
            $nama_poster = time() . '.' . $poster->getClientOriginalExtension();
            $poster->move(public_path('avatar'), $nama_poster);
        } else {
            // Jika tidak ada file avatar yang diunggah, gunakan avatar yang saat ini
            $nama_poster = Auth::user()->avatar;
        }

        // Update informasi pengguna
        $user = User::findOrFail(Auth::user()->id);
        $user->name = $request->name;
        $user->namalengkap = $request->namalengkap;
        $user->email = $request->email;
        $user->avatar = $nama_poster;
        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }
        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
