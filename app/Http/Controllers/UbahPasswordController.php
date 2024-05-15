<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UbahPasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user()->id;
        return view('layouts.ubahpassword', compact('user'));
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

        User::findOrFail(Auth::user()->id)->update([
            'name' => $request->name,
            'namalengkap' => $request->namalengkap,
            'email' => $request->email,
            'avatar' => $request->hasFile('avatar') ? $request->file('avatar')->store('avatars', 'public') : Auth::user()->avatar,
        ]);

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