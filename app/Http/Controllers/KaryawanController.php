<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karyawan = Karyawan::all();
        return view('karyawan.index', compact('karyawan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jabatan = Jabatan::all();
        return view('karyawan.create', compact('jabatan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {
        try {
            $request->validate([
                'nama' => 'required',
                'jabatan_id' => 'required',
                'alamat' => 'required',
                'no_telp' => 'required',
                'nik' => 'required',
                'username' => 'nullable',
                'password' => 'nullable',
            ]);

            $user = $user->create([
                'username' => $request->username,
                'password' => bcrypt($request->password),
            ]);

            Karyawan::create(array_merge($request->all(), ['user_id' => $user->id]));


            return redirect()->route('karyawan.index')
                ->with('success', 'Karyawan created successfully');
        } catch (\Exception $e) {
            return redirect()->route('karyawan.create')
                ->with('error', 'Error creating Karyawan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Karyawan $karyawan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karyawan $karyawan)
    {
        $jabatan = Jabatan::all();
        return view('karyawan.edit', compact('karyawan', 'jabatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Karyawan $karyawan, User $user)
    {
        try {
            $request->validate([
                'nama' => 'required',
                'jabatan_id' => 'required',
                'alamat' => 'required',
                'no_telp' => 'required',
                'nik' => 'required',
                'username' => 'nullable',
                'password' => 'nullable',
            ]);

            $karyawan->update($request->except(['username', 'password']));

            if ($request->filled('username') || $request->filled('password')) {
                $karyawan->user->update([
                    'username' => $request->username,
                    'password' => $request->filled('password') ? bcrypt($request->password) : $karyawan->user->password,
                ]);
            }

            return redirect()->route('karyawan.index')
                ->with('success', 'Karyawan updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('karyawan.edit', $karyawan)
                ->with('error', 'Error updating Karyawan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karyawan $karyawan)
    {
        try {
            $karyawan->delete();

            return redirect()->route('karyawan.index')
                ->with('success', 'Karyawan deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('karyawan.index')
                ->with('error', 'Error deleting Karyawan: ' . $e->getMessage());
        }
    }
}
