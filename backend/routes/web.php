<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//use App\Http\Controllers\EventController;

//Route::get('/events', [EventController::class, 'index']);
//Route::get('/events', [EventController::class, 'store']);
//Route::get('/events/{id}', [EventController::class, 'destroy']);