<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });



Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('master');
    });
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');

    Route::prefix('absensi')->group(function () {
        Route::get('/', [AbsensiController::class, 'index'])->name('absensi.index');
    });

    Route::get('/office-location', function () {
        return response()->json([
            'latitude' => -3.002354,
            'longitude' => 104.725533,
        ]);
    })->name('office-location');

    Route::resource('jabatan', JabatanController::class);
    Route::resource('karyawan', KaryawanController::class);
});
require __DIR__ . '/auth.php';
