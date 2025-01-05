<?php

namespace App\Http\Controllers;

use App\Models\Resign;
use Illuminate\Http\Request;

class ResignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->jabatan == 'karyawan') {
            $data = Resign::where('id_karyawan', auth()->user()->id)->get();
        } else {
            $data = Resign::all();
        }
        return view('resign.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('resign.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'id_karyawan' => 'required',
            'surat_resign' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        try {
            if ($request->hasFile('surat_resign')) {
                $file = $request->file('surat_resign');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/surat_resign', $filename);
                $request->merge(['surat_resign' => $filename]);
            }

            $resign = new Resign();
            $resign->id_karyawan = $request->id_karyawan;
            $resign->surat = $filename;
            $resign->save();
            return redirect()->route('resign.index')->with('success', 'Data berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->route('resign.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $resign = Resign::find($id);
        return view('resign.edit', compact('resign'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'surat_resign' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        try {
            if ($request->hasFile('surat_resign')) {
                $file = $request->file('surat_resign');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/surat_resign', $filename);
                $request->merge(['surat_resign' => $filename]);
            }

            $resign = Resign::find($id);
            $resign->surat = $filename;
            $resign->save();
            return redirect()->route('resign.index')->with('success', 'Data berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->route('resign.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $resign = Resign::find($id);
            $resign->delete();
            return redirect()->route('resign.index')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('resign.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function status($id, Request $request)
    {
        try {
            $resign = Resign::find($id);
            $resign->status = $request->status;
            $resign->save();
            return redirect()->route('resign.index')->with('success', 'Data berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->route('resign.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
