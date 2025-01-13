<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage as FacadesStorage;

class AbsensiController extends Controller
{
    // Lokasi kantor (latitude, longitude)
    private $officeLatitude = -2.932775; // Ganti dengan lokasi kantor
    private $officeLongitude = 104.7181155; // Ganti dengan lokasi kantor
    private $maxDistance = 50; // Dalam meter


    function index()
    {
        $dataAbsen = Absensi::with('user') // Pastikan relasi ke User didefinisikan
            ->orderBy('id_karyawan')
            ->orderBy('created_at')
            ->get();

        if (auth()->user()->jabatan == 'Karyawan') {
            $dataAbsen = Absensi::with('user')
                ->where('id_karyawan', auth()->id())
                ->orderBy('id_karyawan')
                ->orderBy('created_at')
                ->get();
        }

        return view('absensi.index', compact('dataAbsen'));
    }


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'latitude' => 'required|numeric',
    //         'longitude' => 'required|numeric',
    //     ]);

    //     // Hitung jarak menggunakan Haversine formula
    //     $distance = $this->calculateDistance(
    //         $this->officeLatitude,
    //         $this->officeLongitude,
    //         $request->latitude,
    //         $request->longitude
    //     );

    //     // Tentukan status
    //     $isWithinRange = $distance <= $this->maxDistance;

    //     // Simpan data absensi
    //     Absensi::create([
    //         // 'id_karyawan' => auth()->id(),
    //         'tanggal' => now()->toDateString(),
    //         'waktu' => now()->toTimeString(),
    //         'latitude' => $request->latitude,
    //         'longitude' => $request->longitude,
    //         'status' => $isWithinRange,
    //     ]);
    //     return back()->with([
    //         'message' => $isWithinRange ? 'Absen berhasil' : 'Anda di luar jangkauan kantor',
    //         'latitude' => $request->latitude,
    //         'longitude' => $request->longitude,
    //     ]);
    // }

    public function store(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'optionsRadios' => 'required|string',
                // 'locationData' => 'required|url',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'photoData' => 'required|string',
            ]);

            // Ambil data photoData yang berupa string base64
            $photoData = $request->input('photoData');

            // Memisahkan data base64 dari informasi MIME type
            list($type, $data) = explode(';', $photoData);
            list(, $data) = explode(',', $data);

            // Decode base64 menjadi file binary
            $imageData = base64_decode($data);

            // Buat nama file untuk gambar
            $imageName = 'photo_' . time() . '.png'; // Anda bisa menyesuaikan format nama file

            // Tentukan path penyimpanan gambar
            $path = 'absensi/photos/' . $imageName; // Simpan di folder 'absensi/photos'

            // Simpan gambar ke storage
            FacadesStorage::disk('public')->put($path, $imageData);

            // Menyimpan data lainnya ke database
            $absensi = new Absensi();
            $absensi->keterangan = $validated['optionsRadios'];
            // $absensi->location_data = $validated['locationData'];
            $absensi->latitude = $validated['latitude'];
            $absensi->longitude = $validated['longitude'];
            $absensi->id_karyawan = auth()->user()->id;
            $absensi->foto = $path; // Simpan path gambar di database
            $absensi->save();

            return redirect()->route('absensi.index')->with('success', 'Absensi berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }


    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // dalam meter

        $lat1Rad = deg2rad($lat1);
        $lat2Rad = deg2rad($lat2);
        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLon = deg2rad($lon2 - $lon1);

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
            cos($lat1Rad) * cos($lat2Rad) *
            sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function rekap(Request $request)
    {
        $tanggal_dari = $request->input('tanggal_dari');
        $tanggal_sampai = $request->input('tanggal_sampai');

        if (!$tanggal_dari || !$tanggal_sampai) {
            return back()->withErrors(['error' => 'Tanggal dari dan sampai harus diisi!']);
        }

        $tanggal_range = collect();

        // Generate range tanggal
        $start = Carbon::parse($tanggal_dari);
        $end = Carbon::parse($tanggal_sampai);
        while ($start->lte($end)) {
            $tanggal_range->push($start->format('Y-m-d'));
            $start->addDay();
        }

        // Ambil data karyawan dan absensi
        $data_karyawan = User::where('jabatan', 'Karyawan')->with(['absensi' => function ($query) use ($tanggal_dari, $tanggal_sampai) {
            $query->whereBetween('created_at', [$tanggal_dari, $tanggal_sampai]);
        }])->get()->map(function ($karyawan) use ($tanggal_range) {
            // Group absensi berdasarkan tanggal
            $absensi = $karyawan->absensi->groupBy(function ($absensi) {
                return Carbon::parse($absensi->created_at)->format('Y-m-d');
            });

            // Hitung kehadiran unik per tanggal (hanya "masuk")
            $hadir = $absensi->filter(function ($records) {
                return $records->pluck('keterangan')->contains('masuk');
            })->count();

            $persentase = ($hadir / $tanggal_range->count()) * 100;

            // Map absensi per tanggal
            $mapped_absensi = $tanggal_range->mapWithKeys(function ($date) use ($absensi) {
                if ($absensi->has($date)) {
                    $keterangan = $absensi[$date]->pluck('keterangan')->unique()->join(', ');
                } else {
                    $keterangan = '-';
                }

                return [$date => $keterangan];
            });

            return [
                'nama' => $karyawan->nama,
                'absensi' => $mapped_absensi,
                'persentase' => round($persentase, 2),
            ];
        });
        // return view('absensi.rekapPdf', compact('tanggal_dari', 'tanggal_sampai', 'tanggal_range', 'data_karyawan'));
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('absensi.rekapPdf', compact('tanggal_dari', 'tanggal_sampai', 'tanggal_range', 'data_karyawan'))
            ->setPaper('a3', 'landscape');
        return $pdf->download('rekap-absensi.pdf');
    }
}
