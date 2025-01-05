<?php

namespace App\Http\Controllers;

use App\Models\Latlong;
use Illuminate\Http\Request;

class LatlongController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Latlong::all();
        return view('lokasi.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lokasi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'location' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ]);

            Latlong::create($request->all());
            return redirect()->route('location.index')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->route('location.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Latlong $latlong)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Latlong::find($id);
        return view('lokasi.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        try {
            $request->validate([
                'location' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ]);

            $latlong = Latlong::findOrFail($id);
            $latlong->update($request->all());
            return redirect()->route('location.index')->with('success', 'Data berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->route('location.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $latlong = Latlong::findOrFail($id);
            $latlong->delete();
            return redirect()->route('location.index')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('location.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    function getLokasi()
    {
        $data = Latlong::where('location', 'Kantor')->first();
        if ($data) {
            return response()->json([
                'latitude' => $data->latitude,
                'longitude' => $data->longitude,
            ]);
        } else {
            return response()->json([
                'error' => 'Data not found',
            ], 404);
        }
    }
}
