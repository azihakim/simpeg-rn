<?php

namespace App\Http\Controllers;

use App\Models\RewardPunishment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RewardPunishmentController extends Controller
{
    public function index()
    {
        $rewardPunishments = RewardPunishment::with('karyawan')->get();
        if (auth()->user()->jabatan === 'Karyawan') {
            $rewardPunishments = $rewardPunishments->where('id_karyawan', auth()->id());
        }
        return view('rewardpunishment.index', compact('rewardPunishments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $karyawan = User::with('punishments')
            ->where('jabatan', 'Karyawan')
            ->get()
            ->map(function ($user) {
                $user->has_punishment = $user->punishments->isNotEmpty();
                return $user;
            });
        return view('rewardpunishment.create', compact('karyawan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'id_karyawan' => 'required|exists:users,id',
                'jenis' => 'required|in:Reward,Punishment',
                'tanggal' => 'required|date',
                'keterangan' => 'nullable|string',
                'reward' => 'nullable|integer|required_if:jenis,Reward',
                'surat_punishment' => 'nullable|file|mimes:pdf,jpg,png|required_if:jenis,Punishment',
            ]);

            $data = $request->only(['id_karyawan', 'jenis', 'tanggal', 'keterangan', 'reward']);

            // Upload file jika jenis adalah Punishment
            if ($request->hasFile('surat_punishment')) {
                $file = $request->file('surat_punishment');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/surat_punishment', $filename);
                $data['surat_punishment'] = $path;
            }

            // Default status
            $data['status'] = 'Menunggu';

            RewardPunishment::create($data);

            return redirect()->route('rewardpunishment.index')->with('success', 'Data berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->route('rewardpunishment.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $rewardPunishment = RewardPunishment::findOrFail($id);
            $karyawan = User::all(); // Ambil data karyawan untuk dropdown

            return view('rewardpunishment.edit', compact('rewardPunishment', 'karyawan'));
        } catch (\Exception $e) {
            return redirect()->route('rewardpunishment.index')->with('error', 'Data tidak ditemukan');
        }
    }


    public function update(Request $request, $id)
    {
        try {
            // Validasi input
            $request->validate([
                'id_karyawan' => 'required|exists:users,id',
                'jenis' => 'required|in:Reward,Punishment',
                'tanggal' => 'required|date',
                'keterangan' => 'nullable|string',
                'reward' => 'nullable|integer|required_if:jenis,Reward',
                'surat_punishment' => 'nullable|file|mimes:pdf,jpg,png|required_if:jenis,Punishment',
            ]);

            $rewardPunishment = RewardPunishment::findOrFail($id);

            $data = $request->only(['id_karyawan', 'jenis', 'tanggal', 'keterangan']);

            // Jika jenis adalah Reward
            if ($request->jenis === 'Reward') {
                $data['reward'] = $request->reward; // Tetapkan reward dari request
                $data['surat_punishment'] = null; // Hapus surat punishment jika ada

                // Hapus file lama jika ada
                if ($rewardPunishment->surat_punishment && Storage::exists($rewardPunishment->surat_punishment)) {
                    Storage::delete($rewardPunishment->surat_punishment);
                }
            }

            // Jika jenis adalah Punishment
            if ($request->jenis === 'Punishment') {
                $data['reward'] = null; // Hapus nilai reward

                // Jika ada file baru untuk surat punishment
                if ($request->hasFile('surat_punishment')) {
                    $file = $request->file('surat_punishment');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('public/surat_punishment', $filename);

                    // Hapus file lama jika ada
                    if ($rewardPunishment->surat_punishment && Storage::exists($rewardPunishment->surat_punishment)) {
                        Storage::delete($rewardPunishment->surat_punishment);
                    }

                    $data['surat_punishment'] = $path;
                } else {
                    // Gunakan file lama jika tidak ada upload baru
                    $data['surat_punishment'] = $rewardPunishment->surat_punishment;
                }
            }

            $rewardPunishment->update($data);

            return redirect()->route('rewardpunishment.index')->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->route('rewardpunishment.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $rewardPunishment = RewardPunishment::findOrFail($id);

            // Hapus file jika jenis adalah Punishment
            if ($rewardPunishment->jenis === 'Punishment' && $rewardPunishment->surat_punishment && Storage::exists($rewardPunishment->surat_punishment)) {
                Storage::delete($rewardPunishment->surat_punishment);
            }

            $rewardPunishment->delete();

            return redirect()->route('rewardpunishment.index')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('rewardpunishment.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function status(Request $request, $id)
    {
        try {

            $rewardPunishment = RewardPunishment::findOrFail($id);
            $rewardPunishment->update(['status' => $request->status]);

            return redirect()->route('rewardpunishment.index')->with('success', 'Status berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->route('rewardpunishment.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
