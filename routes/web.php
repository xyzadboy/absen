<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsenController;

Route::get('/scan-absen', [AbsenController::class, 'scanPage'])->name('scan-absen');
Route::post('/scan-absen', [AbsenController::class, 'processQrCode'])->name('process-absen');




Route::get('/', function () {
    return view('welcome');
});
