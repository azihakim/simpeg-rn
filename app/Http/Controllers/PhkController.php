<?php

namespace App\Http\Controllers;

use App\Models\Phk;
use App\Models\User;
use Illuminate\Http\Request;

class PhkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Phk::all();
        return view('phk.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $karyawan = User::where('jabatan', 'karyawan')->get();
        return view('phk.create', compact('karyawan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'user_id' => 'required',
            'surat_phk' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        try {
            if ($request->hasFile('surat_phk')) {
                $file = $request->file('surat_phk');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/surat_phk', $filename);
                $request->merge(['surat_phk' => $filename]);
            }

            $phk = new Phk();
            $phk->user_id = $request->user_id;
            $phk->surat = $filename;
            $phk->keterangan = $request->keterangan;
            $phk->save();
            return redirect()->route('phk.index')->with('success', 'Data berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->route('phk.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $data = phk::find($id);
        return view('phk.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'surat_phk' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        try {
            if ($request->hasFile('surat_phk')) {
                $file = $request->file('surat_phk');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/surat_phk', $filename);
                $request->merge(['surat_phk' => $filename]);
            }

            $phk = phk::find($id);
            $phk->surat = $filename;
            $phk->keterangan = $request->keterangan;
            $phk->save();
            return redirect()->route('phk.index')->with('success', 'Data berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->route('phk.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $phk = phk::find($id);
            $phk->delete();
            return redirect()->route('phk.index')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('phk.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function status($id, Request $request)
    {
        try {
            $phk = phk::find($id);
            $phk->status = $request->status;
            $phk->save();
            return redirect()->route('phk.index')->with('success', 'Data berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->route('phk.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
