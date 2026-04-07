<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndustryContactController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/data', function () {
    return response()->json(['content' => 'Laravel 10 running']);
});

Route::get('/industry-contacts', [IndustryContactController::class, 'index']);

// Create a new contact
Route::post('/industry-contacts', [IndustryContactController::class, 'store']);

Route::get('/industry-contacts/{contact}', [IndustryContactController::class, 'show']);

// Update an existing contact
Route::put('/industry-contacts/{contact}', [IndustryContactController::class, 'update']);

// Delete a contact
Route::delete('/industry-contacts/{contact}', [IndustryContactController::class, 'destroy']);