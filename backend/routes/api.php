<?php

use App\Http\Controllers\NetworkingEventController;
use App\Http\Controllers\NetworkingEventCommentController;
use App\Http\Controllers\NetworkingEventQuestionController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IndustryContactController;
use App\Http\Controllers\StudentLinkController;
use App\Http\Controllers\SmartGoalController;
use App\Http\Controllers\GoalActionStepController;
use App\Http\Controllers\CareerDevelopmentPlanController;
use App\Http\Controllers\CompetencyEntryController;
use App\Http\Controllers\CompetencyIndicatorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

// Student Profile
Route::get('/profile/{id}', [StudentProfileController::class, 'show']);
Route::put('/profile/{id}', [StudentProfileController::class, 'update']);

// Industry contacts/networking pages
Route::get('/users/{user}/industry-contacts', [IndustryContactController::class, 'index']);
Route::post('/users/{user}/industry-contacts', [IndustryContactController::class, 'store']);
Route::get('/users/{user}/industry-contacts/{industryContact}', [IndustryContactController::class, 'show']);
Route::put('/users/{user}/industry-contacts/{industryContact}', [IndustryContactController::class, 'update']);
Route::delete('/users/{user}/industry-contacts/{industryContact}', [IndustryContactController::class, 'destroy']);

// Student Profile Links
Route::post('/link', [StudentLinkController::class, 'store']);
Route::put('/link/{id}', [StudentLinkController::class, 'update']);
Route::delete('/link/{id}', [StudentLinkController::class, 'destroy']);

// Smart Goal routes
Route::get('/smart-goals', [SmartGoalController::class, 'index']);
Route::post('/smart-goals', [SmartGoalController::class, 'store']);
Route::get('/smart-goals/{id}', [SmartGoalController::class, 'show']);
Route::put('/smart-goals/{id}', [SmartGoalController::class, 'update']);
Route::delete('/smart-goals/{id}', [SmartGoalController::class, 'destroy']);
Route::put('/smart-goals/{goalId}/action-steps', [SmartGoalController::class, 'replaceActionSteps']);
Route::get('/user/smart-goals/{userId}', [SmartGoalController::class, 'showUserGoals']);
Route::post('/smart-goals/{goalId}/action-steps', [GoalActionStepController::class, 'store']);
Route::put('/action-steps/{stepId}', [GoalActionStepController::class, 'update']);
Route::delete('/action-steps/{stepId}', [GoalActionStepController::class, 'destroy']);

// Career Development Plan routes
Route::get('/career-plans', [CareerDevelopmentPlanController::class, 'index']);
Route::get('/career-plans/{id}', [CareerDevelopmentPlanController::class, 'show']);

// Competency Entries
Route::get('/competency-entries/{id}', [CompetencyEntryController::class, 'show']);

// Competency Indicators
Route::get('/competency-indicators', [CompetencyIndicatorController::class, 'index']);

// Event
Route::get('/networking-events', [NetworkingEventController::class, 'index']);
Route::post('/networking-events', [NetworkingEventController::class, 'store']);
Route::get('/networking-events/{networkingEvent}', [NetworkingEventController::class, 'show']);
Route::put('/networking-events/{networkingEvent}', [NetworkingEventController::class, 'update']);
Route::delete('/networking-events/{networkingEvent}', [NetworkingEventController::class, 'destroy']);

// Questions
Route::get('/networking-events/{id}/questions', [NetworkingEventQuestionController::class, 'index']);
Route::post('/networking-events/{id}/questions', [NetworkingEventQuestionController::class, 'store']);
Route::put('/questions/{id}', [NetworkingEventQuestionController::class, 'update']);
Route::delete('/questions/{id}', [NetworkingEventQuestionController::class, 'destroy']);

// Comment
Route::get('/networking-events/{id}/comments', [NetworkingEventCommentController::class, 'index']);
Route::post('/networking-events/{id}/comments', [NetworkingEventCommentController::class, 'store']);
Route::put('/comments/{id}', [NetworkingEventCommentController::class, 'update']);
Route::delete('/comments/{id}', [NetworkingEventCommentController::class, 'destroy']);
