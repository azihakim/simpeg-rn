<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\CutiIzin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CutiIzinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->jabatan == 'karyawan') {
            $data = CutiIzin::where('id_karyawan', auth()->user()->id)->get();
        } else {
            $data = CutiIzin::all();
        }

        return view('cutiizin.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kuota = $this->checkQuota(auth()->user()->id);
        return view('cutiizin.create', compact('kuota'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $kuotaResponse = $this->checkQuota(Auth::user()->id);
        $kuota = $kuotaResponse->getData(true);

        $start = strtotime($request->tanggal_mulai);
        $end = strtotime($request->tanggal_selesai);
        $daysRequested = ($end - $start) / (60 * 60 * 24) + 1;

        if ($kuota['remaining_quota'] < $daysRequested) {
            return redirect()->back()->with('error', 'Kuota tidak mencukupi. Sisa kuota: ' . $kuota['remaining_quota'] . ' hari');
        }

        $data = new CutiIzin();
        $data->id_karyawan = Auth::user()->id;
        $data->jenis = $request->jenis;
        $data->tanggal_mulai = $request->tanggal_mulai;
        $data->tanggal_selesai = $request->tanggal_selesai;
        $data->keterangan = $request->keterangan;
        $data->status = 'Menunggu';
        $data->save();

        return redirect()->route('cutiizin.index')->with('success', 'Data berhasil disimpan.');
    }

    public function checkQuota($id_karyawan)
    {
        $kuota = 15;
        $tahun = date('Y');
        $totalRecently = CutiIzin::where('id_karyawan', $id_karyawan)
            ->whereYear('tanggal_mulai', $tahun)
            ->where('status', 'Diterima')
            ->get()
            ->sum(function ($cuti) {
                $start = strtotime($cuti->tanggal_mulai);
                $end = strtotime($cuti->tanggal_selesai);
                return ($end - $start) / (60 * 60 * 24) + 1;
            });

        $remainingQuota = $kuota - $totalRecently;

        return response()->json([
            'totalRecently' => $totalRecently,
            'remaining_quota' => $remainingQuota
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(CutiIzin $cutiIzin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = CutiIzin::find($id);
        return view('cutiizin.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = CutiIzin::find($id);
        $data->jenis = $request->jenis;
        $data->tanggal_mulai = $request->tanggal_mulai;
        $data->tanggal_selesai = $request->tanggal_selesai;
        $data->keterangan = $request->keterangan;
        $data->save();
        return redirect()->route('cutiizin.index')->with('success', 'Data berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = CutiIzin::find($id);
        $data->delete();
        return redirect()->route('cutiizin.index')->with('success', 'Data berhasil dihapus.');
    }

    public function status(Request $request, $id)
    {



        $data = CutiIzin::find($id);
        $data->status = $request->status;

        $absensi = new Absensi();
        $absensi->id_karyawan = $data->id_karyawan;
        $absensi->created_at = $data->tanggal_mulai;
        $absensi->created_at = $data->tanggal_selesai;
        $start = strtotime($data->tanggal_mulai);
        $end = strtotime($data->tanggal_selesai);

        $days = ($end - $start) / (60 * 60 * 24) + 1;
        $kuotaResponse = $this->checkQuota($data->id_karyawan);
        $kuota = $kuotaResponse->getData(true);
        if ($kuota['remaining_quota'] < $days) {
            return redirect()->route('cutiizin.index')->with('error', 'Kuota tidak mencukupi. Pengajuan cuti dibatalkan.');
        }
        for ($date = $start; $date <= $end; $date = strtotime('+1 day', $date)) {
            $absensi = new Absensi();
            $absensi->id_karyawan = $data->id_karyawan;
            $absensi->created_at = date('Y-m-d', $date);
            $absensi->keterangan = $data->jenis;
            $absensi->foto = $data->jenis;
            $absensi->save();
        }

        $data->save();

        // $data->save();
        return redirect()->route('cutiizin.index')->with('success', 'Data berhasil disetujui.');
    }
}
