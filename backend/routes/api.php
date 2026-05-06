<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\IndustryContactController;
use App\Http\Controllers\StudentLinkController;
use App\Http\Controllers\SmartGoalController;
use App\Http\Controllers\GoalActionStepController;
use App\Http\Controllers\CareerDevelopmentPlanController;
use App\Http\Controllers\CompetencyEntryController;
use App\Http\Controllers\CompetencyIndicatorController;
use App\Http\Controllers\AchievementCertController;
use App\Http\Controllers\AttainmentCertController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GoalStatusesController;

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
Route::get('/profileDash/{id}', [StudentProfileController::class, 'getDashboardInfo']);


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
// Keep this static route above /smart-goals/{id} so "reorder" is not matched as an ID.
Route::put('/smart-goals/reorder', [SmartGoalController::class, 'reorder']);
Route::get('/smart-goals/{id}', [SmartGoalController::class, 'show']);
Route::put('/smart-goals/{id}', [SmartGoalController::class, 'update']);
Route::delete('/smart-goals/{id}', [SmartGoalController::class, 'destroy']);
Route::put('/smart-goals/{goalId}/action-steps', [SmartGoalController::class, 'replaceActionSteps']);
Route::get('user/smart-goals/{userId}', [SmartGoalController::class, 'showUserGoals']);
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

// Export profile data as pdf
Route::post('/profile/{id}/export-pdf', [StudentProfileController::class, 'exportPdf']);

// Achievement certificates
Route::post('/achievement-cert', [AchievementCertController::class, 'store']);
Route::put('/achievement-cert/{id}', [AchievementCertController::class, 'update']);
Route::delete('/achievement-cert/{id}', [AchievementCertController::class, 'destroy']);

// Attainment certificates
Route::post('/attainment-cert', [AttainmentCertController::class, 'store']);
Route::put('/attainment-cert/{id}', [AttainmentCertController::class, 'update']);
Route::delete('/attainment-cert/{id}', [AttainmentCertController::class, 'destroy']);

// Goal status
Route::get('/goal-status', [GoalStatusesController::class, 'index']);
Route::post('/goal-status', [GoalStatusesController::class, 'store']);
Route::put('/goal-status/{status}', [GoalStatusesController::class, 'update']);
Route::delete('/goal-status/{status}', [GoalStatusesController::class, 'destroy']);

// Admin dashboard
Route::get('/admin/users-overview', [AdminController::class, 'usersOverview']);