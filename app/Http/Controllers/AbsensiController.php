<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
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
        $dataAbsen = Absensi::all();
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
    //         // 'user_id' => auth()->id(),
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
        // Validasi input
        $validated = $request->validate([
            'optionsRadios' => 'required|string',
            'locationData' => 'required|url',
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
        $absensi->user_id =  auth()->user()->id;
        $absensi->foto = $path; // Simpan path gambar di database
        $absensi->save();

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil disimpan.');
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
}
