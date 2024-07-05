<?php

use App\Http\Controllers\SubKriteriaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::get('/subkriteria/bykriteriaid/{id}', [SubKriteriaController::class, 'bykriteriaid'])
->name('api.subkriteria.bykriteriaid');

Route::post('/subkriteria', [SubKriteriaController::class, 'store'])->name('api.subkriteria.store');
Route::put('/subkriteria/{id}', [SubKriteriaController::class, 'update'])->name('api.subkriteria.update');
Route::delete('/subkriteria/{id}', [SubKriteriaController::class, 'destroy'])->name('api.subkriteria.destroy');
