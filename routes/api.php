<?php

use App\Http\Controllers\Api\PublicDataApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — PORMA Diskominfo Garut
|--------------------------------------------------------------------------
|
| Endpoints di bawah ini dilindungi oleh middleware 'api.key'.
| Sistem eksternal wajib menyertakan header 'X-API-KEY: porma_...'
| atau 'Authorization: Bearer porma_...'.
|
*/

Route::prefix('v1')->middleware(['api.key'])->group(function () {
    // Data Bidang & Status Kuota
    Route::get('/departments', [PublicDataApiController::class, 'departments']);

    // Data Pendaftar / Peserta
    Route::get('/registrations', [PublicDataApiController::class, 'registrations']);

    // Data Ringkasan Statistik
    Route::get('/statistics', [PublicDataApiController::class, 'statistics']);
});
