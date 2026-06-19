<?php

use App\Http\Controllers\PanelController;
use Illuminate\Support\Facades\Route;

Route::get('/health', [PanelController::class, 'health']);
Route::get('/sample', [PanelController::class, 'sample']);
Route::get('/sample/elevation', [PanelController::class, 'sampleElevation']);
Route::post('/panels', [PanelController::class, 'store']);
Route::post('/panels/elevation', [PanelController::class, 'elevation']);
