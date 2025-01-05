<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\PromosiDemosi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PromosiDemosiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = PromosiDemosi::all();
        if (auth()->user()->jabatan === 'Karyawan') {
            $data = $data->where('id_karyawan', auth()->id());
        }
        return view('promosidemosi.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $karyawan = User::with('divisi')->where('jabatan', 'Karyawan')->get();


        $divisi = Jabatan::all();
        return view('promosidemosi.create', compact('karyawan', 'divisi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi data
            $validatedData = $request->validate([
                'id_karyawan' => 'required|integer',
                'jenis' => 'required|string|max:255',
                'divisi_lama_id' => 'required|max:255',
                'divisi_baru_id' => 'required|max:255',
                'surat_rekomendasi' => 'required|file|mimes:pdf,doc,docx|max:2048',
            ]);

            // Proses unggah file
            if ($request->hasFile('surat_rekomendasi')) {
                $file = $request->file('surat_rekomendasi');
                $filename = 'rekomendasi_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('surat_rekomendasi', $filename, 'public');
                $validatedData['surat_rekomendasi'] = $filePath; // Simpan path file ke database
            }

            // Simpan data ke database
            PromosiDemosi::create($validatedData);

            return redirect()->route('promosidemosi.index')->with('success', 'Data berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->route('promosidemosi.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PromosiDemosi $promosiDemosi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = PromosiDemosi::find($id);
        $karyawan = User::with('divisi')->where('jabatan', 'Karyawan')->first();
        $allKaryawan = User::where('jabatan', 'Karyawan')->get();
        $divisi = Jabatan::all();
        return view('promosidemosi.edit', compact('data', 'karyawan', 'allKaryawan', 'divisi'));
    }

    public function update($id, Request $request)
    {
        try {
            $promosiDemosi = PromosiDemosi::findOrFail($id);

            // Validasi data
            $validatedData = $request->validate([
                'id_karyawan' => 'required|integer',
                'jenis' => 'required|string|max:255',
                'divisi_lama_id' => 'required',
                'divisi_baru_id' => 'required',
                'surat_rekomendasi' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);

            // Proses file jika ada file baru
            if ($request->hasFile('surat_rekomendasi')) {
                $file = $request->file('surat_rekomendasi');
                $filename = 'rekomendasi_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('surat_rekomendasi', $filename, 'public');

                // Hapus file lama
                if ($promosiDemosi->surat_rekomendasi) {
                    Storage::disk('public')->delete($promosiDemosi->surat_rekomendasi);
                }

                // Update file path
                $validatedData['surat_rekomendasi'] = $filePath;
            } else {
                $validatedData['surat_rekomendasi'] = $promosiDemosi->surat_rekomendasi; // Tetap gunakan file lama
            }

            // Update data ke database
            $promosiDemosi->update($validatedData);

            return redirect()->route('promosidemosi.index')->with('success', 'Data berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->route('promosidemosi.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, PromosiDemosi $promosiDemosi)
    {
        try {
            $promosiDemosi = PromosiDemosi::findOrFail($id);
            $promosiDemosi->delete();
            return redirect()->route('promosidemosi.index')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('promosidemosi.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function status($id, Request $request)
    {
        try {
            $promosiDemosi = PromosiDemosi::findOrFail($id);
            $promosiDemosi->status = $request->status;
            if ($request->status == 'Diterima') {
                $promosiDemosi->karyawan->update([
                    'divisi_id' => $promosiDemosi->divisi_baru
                ]);
            }
            $promosiDemosi->save();
            return redirect()->route('promosidemosi.index')->with('success', 'Data berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->route('promosidemosi.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
