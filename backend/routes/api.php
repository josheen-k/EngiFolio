<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\TestStudentController;
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

Route::get('/test-students', [TestStudentController::class, 'index']);
Route::post('/test-students', [TestStudentController::class, 'store']);
Route::get('/test-students/{id}', [TestStudentController::class, 'show']);
Route::put('/test-students/{id}', [TestStudentController::class, 'update']);
Route::delete('/test-students/{id}', [TestStudentController::class, 'destroy']);