<?php

namespace App\Http\Controllers;

use App\Models\Pelamar;
use App\Models\User;
use Illuminate\Http\Request;

class RegistrasiController extends Controller
{
    public function create()
    {
        return view('auth.registrasi');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        try {
            $user = new User();
            $user->role = "Pelamar";
            $user->username = $request->username;
            $user->password = bcrypt($request->password);
            $user->save();

            $karyawan = new Pelamar();
            $karyawan->nama = $request->nama;
            $karyawan->no_telp = $request->no_telp;
            $karyawan->umur = $request->umur;
            $karyawan->alamat = $request->alamat;
            $karyawan->jenis_kelamin = $request->jenis_kelamin;
            $karyawan->user_id = $user->id; // Assuming you have a foreign key relationship
            $karyawan->save();

            return redirect()->route('login')->with('success', 'Registrasi berhasil');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
