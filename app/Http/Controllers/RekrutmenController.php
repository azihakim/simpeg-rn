<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Lowongan;
use App\Models\Rekrutmen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RekrutmenController extends Controller
{
    public function lowonganIndex()
    {
        if (auth()->user()->jabatan == 'Pelamar') {
            $lowongan = Lowongan::where('status', 'Aktif')->get();
        } else {
            $lowongan = Lowongan::all();
        }
        return view('rekrutmen.lowongan.index', compact('lowongan'));
    }

    public function lowonganCreate()
    {
        $jabatan = Jabatan::all();
        return view('rekrutmen.lowongan.create', compact('jabatan'));
    }

    public function lowonganStore(Request $request)
    {
        $request->validate([
            'jabatan' => 'required',
            'deskripsi' => 'required',
        ]);

        try {
            Lowongan::create($request->all());
            return redirect()->route('lowongan.index')->with('success', 'Lowongan berhasil dibuat');
        } catch (\Exception $e) {
            Log::error('Error creating lowongan: ' . $e->getMessage());
            return redirect()->route('lowongan.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function lowonganEdit($id)
    {
        $lowongan = Lowongan::find($id);
        $jabatan = Jabatan::all();
        return view('rekrutmen.lowongan.edit', compact('lowongan', 'jabatan'));
    }

    public function lowonganUpdate(Request $request, $id)
    {
        $request->validate([
            'jabatan' => 'required',
            'status' => 'required',
            'deskripsi' => 'required',
        ]);

        try {
            Lowongan::find($id)->update($request->all());
            return redirect()->route('lowongan.index')->with('success', 'Lowongan berhasil diupdate');
        } catch (\Exception $e) {
            Log::error('Error updating lowongan: ' . $e->getMessage());
            return redirect()->route('lowongan.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function lowonganDestroy($id)
    {
        try {
            Lowongan::destroy($id);
            return redirect()->route('lowongan.index')->with('success', 'Lowongan berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting lowongan: ' . $e->getMessage());
            return redirect()->route('lowongan.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    // LAMARAN
    public function lamaranIndex()
    {
        $lamaran = Rekrutmen::all();
        if (auth()->user()->jabatan == 'Pelamar') {
            $lamaran = Rekrutmen::where('id_pelamar', auth()->id())->get();
        }
        return view('rekrutmen.lamaran.index', compact('lamaran'));
    }

    public function lamaranRegist($id)
    {
        $lowongan = Lowongan::find($id);
        return view('rekrutmen.lamaran.regist', compact('lowongan'));
    }

    public function lamaranStore(Request $request)
    {
        $request->validate([
            'id_lowongan' => 'required',
            'file' => 'required|file|mimes:pdf,doc,docx|max:5048',
        ]);
        try {
            // Handle file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = auth()->user()->nama . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('lamaran_files', $filename, 'public');
            }

            // Create Rekrutmen entry
            Rekrutmen::create([
                'id_lowongan' => $request->id_lowongan,
                'id_pelamar' => auth()->id(),
                'file' => $filename,
            ]);

            return redirect()->route('lamaran.index')->with('success', 'Lamaran berhasil dibuat');
        } catch (\Exception $e) {
            Log::error('Error creating lamaran: ' . $e->getMessage());
            return redirect()->route('lamaran.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function lamaranEdit($id)
    {
        $data = Rekrutmen::find($id);
        return view('rekrutmen.lamaran.edit', compact('data'));
    }

    public function lamaranUpdate(Request $request, $id)
    {
        $request->validate([
            'id_lowongan' => 'required',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:5048',
        ]);

        try {
            $data = [
                'id_lowongan' => $request->id_lowongan,
            ];

            // Handle file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = auth()->user()->nama . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('lamaran_files', $filename, 'public');
                $data['file'] = $filename;
            }

            // Update Rekrutmen entry
            Rekrutmen::find($id)->update($data);

            return redirect()->back()->with('success', 'Lamaran berhasil diupdate');
        } catch (\Exception $e) {
            Log::error('Error updating lamaran: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function lamaranStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
        ]);

        try {
            Rekrutmen::find($id)->update([
                'status' => $request->status,
            ]);

            return redirect()->back()->with('success', 'Status lamaran berhasil diupdate');
        } catch (\Exception $e) {
            Log::error('Error updating lamaran status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
