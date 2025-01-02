<?php

namespace App\Http\Controllers;

use App\Models\Penugasan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenugasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Penugasan::all();
        return view('penugasan.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $karyawan = User::where('jabatan', 'karyawan')->get();
        return view('penugasan.create', compact('karyawan'));
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'user_id' => 'required',
        //     'surat' => 'required|file|mimes:pdf,doc,docx|max:2048',
        //     'keterangan' => 'required',
        // ]);
        if ($request->hasFile('surat')) {
            $file = $request->file('surat');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/surat_penugasan', $filename);
            $request->merge(['surat' => $filename]);

            $penugasan = new Penugasan([
                'user_id' => $request->user_id,
                'surat' => $filename,
                'keterangan' => $request->keterangan,
            ]);
            $penugasan->save();
        }

        return redirect()->route('penugasan.index')->with('success', 'Penugasan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Penugasan $penugasan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $penugasan = Penugasan::find($id);
        $karyawan = User::where('jabatan', 'karyawan')->get();
        return view('penugasan.edit', compact('penugasan', 'karyawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $penugasan = Penugasan::find($id);

        // $request->validate([
        //     'user_id' => 'required',
        //     'surat' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        //     'keterangan' => 'required',
        // ]);

        if ($request->hasFile('surat')) {
            $file = $request->file('surat');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/surat_penugasan', $filename);
            $request->merge(['surat' => $filename]);

            // Delete old file
            if ($penugasan->surat) {
                Storage::delete('public/surat_penugasan/' . $penugasan->surat);
            }

            $penugasan->surat = $filename;
        }

        $penugasan->user_id = $penugasan->user_id;
        $penugasan->status = $request->status;
        $penugasan->keterangan = $request->keterangan;
        $penugasan->save();

        return redirect()->route('penugasan.index')->with('success', 'Penugasan berhasil diperbarui');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $penugasan = Penugasan::find($id);
        $penugasan->delete();
        return redirect()->route('penugasan.index')->with('success', 'Penugasan berhasil dihapus');
    }

    public function status($id, Request $request)
    {
        try {
            $penugasan = Penugasan::find($id);
            $penugasan->status = $request->status;
            $penugasan->save();
            return redirect()->route('penugasan.index')->with('success', 'Status penugasan berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->route('penugasan.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
