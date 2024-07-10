<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\SubKriteriaController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/kriteria', [KriteriaController::class, 'index'])->name('kriteria.index');
    Route::get('/kriteria/create', [KriteriaController::class, 'create'])->name('kriteria.create');
    Route::get('/kriteria/bobot', [KriteriaController::class, 'bobot'])->name('kriteria.bobot');

    Route::post('/kriteria', [KriteriaController::class, 'store'])->name('kriteria.store');
    Route::get('/kriteria/{id}', [KriteriaController::class, 'edit'])->name('kriteria.edit');
    Route::put('/kriteria/{id}', [KriteriaController::class, 'update'])->name('kriteria.update');
    Route::delete('/kriteria/{id}', [KriteriaController::class, 'destroy'])->name('kriteria.delete');

    Route::post('/kriteria/bobot', [KriteriaController::class, 'storeBobot'])->name('kriteria.storeBobot');

    Route::get('/subkriteria', [SubKriteriaController::class, 'index'])->name('subkriteria.create');

    Route::post('/subkriteria', [SubKriteriaController::class, 'store'])->name('subkriteria.store');
    Route::get('/subkriteria/{id}', [SubKriteriaController::class, 'edit'])->name('subkriteria.edit');
    Route::put('/subkriteria/{id}', [SubKriteriaController::class, 'update'])->name('subkriteria.update');
    Route::delete('/subkriteria/{id}', [SubKriteriaController::class, 'destroy'])->name('subkriteria.delete');


    //alternatif

    Route::get('/alternatif', [AlternatifController::class, 'index'])->name('alternatif.index');
    Route::get('/alternatif/create', [AlternatifController::class, 'create'])->name('alternatif.create');
    Route::post('/alternatif', [AlternatifController::class, 'store'])->name('alternatif.store');
    Route::get('/alternatif/{id}', [AlternatifController::class, 'edit'])->name('alternatif.edit');
    Route::put('/alternatif/{id}', [AlternatifController::class, 'update'])->name('alternatif.update');
    Route::delete('/alternatif/{id}', [AlternatifController::class, 'destroy'])->name('alternatif.delete');



    //periode
    Route::get('/periode', [PeriodeController::class, 'index'])->name('periode.index');
    Route::get('/periode/create', [PeriodeController::class, 'create'])->name('periode.create');
    Route::post('/periode', [PeriodeController::class, 'store'])->name('periode.store');
    Route::get('/periode/{id}', [PeriodeController::class, 'edit'])->name('periode.edit');
    Route::put('/periode/{id}', [PeriodeController::class, 'update'])->name('periode.update');
    Route::delete('/periode/{id}', [PeriodeController::class, 'destroy'])->name('periode.delete');



    //penilaian
    Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian.index');
    Route::post('/penilaian', [PenilaianController::class, 'store'])->name('penilaian.store');


});




require __DIR__ . '/auth.php';
