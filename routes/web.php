<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrasiController;
use App\Http\Controllers\RekrutmenController;
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



// Route::middleware('auth')->group(function () {
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

    Route::prefix('rekrutmen')->group(function () {
        Route::get('/lowongan', [RekrutmenController::class, 'lowonganIndex'])->name('lowongan.index');
        Route::get('/lowongan/create', [RekrutmenController::class, 'lowonganCreate'])->name('lowongan.create');
        Route::post('/lowongan/store', [RekrutmenController::class, 'lowonganStore'])->name('lowongan.store');
        Route::get('/lowongan/show', [RekrutmenController::class, 'lowonganShow'])->name('lowongan.show');
        Route::get('/lowongan/edit/{id}', [RekrutmenController::class, 'lowonganEdit'])->name('lowongan.edit');
        Route::put('/lowongan/update/{id}', [RekrutmenController::class, 'lowonganUpdate'])->name('lowongan.update');
        Route::delete('/lowongan/{id}', [RekrutmenController::class, 'lowonganDestroy'])->name('lowongan.destroy');

        Route::get('/lamaran', [RekrutmenController::class, 'lamaranIndex'])->name('lamaran.index');
        Route::get('/lamaran/regist/{id}', [RekrutmenController::class, 'lamaranRegist'])->name('lamaran.regist');
        Route::post('/lamaran/store', [RekrutmenController::class, 'lamaranStore'])->name('lamaran.store');
        Route::get('/lamaran/edit/{id}', [RekrutmenController::class, 'lamaranEdit'])->name('lamaran.edit');
        Route::put('/lamaran/update/{id}', [RekrutmenController::class, 'lamaranUpdate'])->name('lamaran.update');
        Route::put('/lamaran/status/{id}', [RekrutmenController::class, 'lamaranStatus'])->name('lamaran.status');
    });
// });

Route::get('/registrasi', [RegistrasiController::class, 'create'])->name('registrasi.form');
Route::post('/registrasi', [RegistrasiController::class, 'store'])->name('registrasi.store');
require __DIR__ . '/auth.php';
