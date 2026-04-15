<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StudentProfileController;

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

Route::get('/profile/{id}', [StudentProfileController::class, 'show']);

Route::put('/profile/{id}', [StudentProfileController::class, 'update']);

use App\Http\Controllers\NetworkingEventController;
use App\Http\Controllers\NetworkingEventQuestionController;

//Event
Route::get('/networking-events', [NetworkingEventController::class, 'index']);
Route::post('/networking-events', [NetworkingEventController::class, 'store']);
Route::get('networking-events/{networkingEvent}', [NetworkingEventController::class, 'show']);
Route::put('networking-events/{networkingEvent}', [NetworkingEventController::class, 'update']);
Route::delete('networking-events/{networkingEvent}', [NetworkingEventController::class, 'destroy']);

//Questions
Route::get('/networking-events/{id}/questions', [NetworkingEventQuestionController::class, 'index']);
Route::post('/networking-events/{id}/questions', [NetworkingEventQuestionController::class, 'store']);
Route::put('/questions/{id}', [NetworkingEventQuestionController::class, 'update']);
Route::delete('/questions/{id}', [NetworkingEventQuestionController::class,'destroy']);