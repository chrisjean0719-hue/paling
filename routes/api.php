<?php

use App\Http\Controllers\PanelController;
use Illuminate\Support\Facades\Route;

Route::get('/health', [PanelController::class, 'health']);
Route::get('/sample', [PanelController::class, 'sample']);
Route::post('/panels', [PanelController::class, 'store']);
