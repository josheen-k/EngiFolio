<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventQuestionController;

Route::apiResource('events', EventController::class);

Route::get('/events/{id}/questions', [EventQuestionController::class, 'index']);
Route::post('/events/{id}/questions', [EventQuestionController::class, 'store']);
Route::put('/questions/{id}', [EventQuestionController::class, 'update']);
Route::delete('/questions/{id}', [EventQuestionController::class, 'destroy']);