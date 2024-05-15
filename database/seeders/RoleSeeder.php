<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'namalengkap' => 'Administrator',
            'jeniskelamin' => 'Laki-laki',
            'alamat' => 'JL.sulaksana',
            'avatar' => 'gambar.png',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'level' => 'Admin',
        ]);
        DB::table('users')->insert([
            'name' => 'Petugas',
            'namalengkap' => 'Petugas',
            'jeniskelamin' => 'Laki-laki',
            'alamat' => 'Jl.padasuka',
            'avatar' => 'gambar.png',
            'email' => 'petugas@gmail.com',
            'password' => Hash::make('petugas'),
            'level' => 'Petugas',
        ]);

    }
}