<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Nouvelle route pour récupérer les zones d'une date spécifique
Route::get('/api/events/{evenement}/zones-for-date/{date}', [ReservationController::class, 'getZonesForDate'])
    ->name('api.zones_for_date');