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
            $user->nama = $request->nama;
            $user->no_telp = $request->no_telp;
            $user->umur = $request->umur;
            $user->alamat = $request->alamat;
            $user->jenis_kelamin = $request->jenis_kelamin;
            $user->save();

            return redirect()->route('login')->with('success', 'Registrasi berhasil');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
