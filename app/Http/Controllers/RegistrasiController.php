<?php

namespace App\Http\Controllers;

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
        try {
            $data = new User();
            $data->nama = $request->nama;
            $data->telepon = $request->telepon;
            $data->umur = $request->umur;
            $data->alamat = $request->alamat;
            $data->jenis_kelamin = $request->jenis_kelamin;
            $data->jabatan = "Pelamar";
            $data->username = $request->username;
            $data->password = bcrypt($request->password);
            $data->save();

            return redirect()->route('login')->with('success', 'Registrasi berhasil');
        } catch (\Exception $e) {
            return back()->with('error', 'Error!');
        }
    }
}
