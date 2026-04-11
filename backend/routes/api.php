<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentProfileController;
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

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{user}', [UserController::class, 'show']);
Route::put('/users/{user}', [UserController::class, 'update']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);


Route::get('/profile/{id}', [StudentProfileController::class, 'show']);

Route::put('/profile/{id}', [StudentProfileController::class, 'update']);

Route::get('/industry_/{id}', [StudentProfileController::class, 'show']);

Route::put('/profile/{id}', [StudentProfileController::class, 'update']);


Route::get('/users/{user_id}/industry-contacts', [IndustryContactController::class, 'index']);
Route::post('/users/{user_id}/industry-contacts', [IndustryContactController::class, 'store']);
Route::get('/users/{user_id}/industry-contacts/{contact_id}', [IndustryContactController::class, 'show']);
Route::put('/users/{user_id}/industry-contacts/{contact_id}', [IndustryContactController::class, 'update']);
Route::delete('/users/{user_id}/industry-contacts/{contact_id}', [IndustryContactController::class, 'destroy']);