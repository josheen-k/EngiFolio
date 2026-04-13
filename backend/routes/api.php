<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\SmartGoalController;
use App\Http\Controllers\CareerDevelopmentPlanController;

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


// Smart Goal routes
Route::get('/smart-goals', [SmartGoalController::class, 'index']);
Route::post('/smart-goals', [SmartGoalController::class, 'store']);
Route::get('/smart-goals/{id}', [SmartGoalController::class, 'show']);
Route::put('/smart-goals/{id}', [SmartGoalController::class, 'update']);
Route::delete('/smart-goals/{id}', [SmartGoalController::class, 'destroy']);

// Career Development Plan routes
Route::get('/career-plans', [CareerDevelopmentPlanController::class, 'index']);