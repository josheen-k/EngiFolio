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
use App\Http\Controllers\CompetencyIndicatorController;
use App\Http\Controllers\AchievementCertController;
use App\Http\Controllers\AttainmentCertController;
use App\Http\Controllers\CompetencyEntryController;
use App\Http\Controllers\CompetencyFeedbackController;
use App\Http\Controllers\MentorStudentMappingController; // ✅ FIXED IMPORT

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/data', function () {
    return response()->json(['content' => 'Laravel 10 running']);
});

/* ================= USERS ================= */

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{user}', [UserController::class, 'show']);
Route::put('/users/{user}', [UserController::class, 'update']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);

/* ================= STUDENT PROFILE ================= */

Route::get('/profile/{id}', [StudentProfileController::class, 'show']);
Route::put('/profile/{id}', [StudentProfileController::class, 'update']);
Route::get('/profileDash/{id}', [StudentProfileController::class, 'getDashboardInfo']);
Route::post('/profile/{id}/export-pdf', [StudentProfileController::class, 'exportPdf']);

/* ================= INDUSTRY CONTACTS ================= */

Route::get('/users/{user}/industry-contacts', [IndustryContactController::class, 'index']);
Route::post('/users/{user}/industry-contacts', [IndustryContactController::class, 'store']);
Route::get('/users/{user}/industry-contacts/{industryContact}', [IndustryContactController::class, 'show']);
Route::put('/users/{user}/industry-contacts/{industryContact}', [IndustryContactController::class, 'update']);
Route::delete('/users/{user}/industry-contacts/{industryContact}', [IndustryContactController::class, 'destroy']);

/* ================= STUDENT LINKS ================= */

Route::post('/link', [StudentLinkController::class, 'store']);
Route::put('/link/{id}', [StudentLinkController::class, 'update']);
Route::delete('/link/{id}', [StudentLinkController::class, 'destroy']);

/* ================= SMART GOALS ================= */

Route::get('/smart-goals', [SmartGoalController::class, 'index']);
Route::post('/smart-goals', [SmartGoalController::class, 'store']);
Route::put('/smart-goals/reorder', [SmartGoalController::class, 'reorder']);
Route::get('/smart-goals/{id}', [SmartGoalController::class, 'show']);
Route::put('/smart-goals/{id}', [SmartGoalController::class, 'update']);
Route::delete('/smart-goals/{id}', [SmartGoalController::class, 'destroy']);
Route::put('/smart-goals/{goalId}/action-steps', [SmartGoalController::class, 'replaceActionSteps']);
Route::get('/user/smart-goals/{userId}', [SmartGoalController::class, 'showUserGoals']);

Route::post('/smart-goals/{goalId}/action-steps', [GoalActionStepController::class, 'store']);
Route::put('/action-steps/{stepId}', [GoalActionStepController::class, 'update']);
Route::delete('/action-steps/{stepId}', [GoalActionStepController::class, 'destroy']);

/* ================= CAREER PLAN ================= */

Route::get('/career-plans', [CareerDevelopmentPlanController::class, 'index']);
Route::get('/career-plans/{id}', [CareerDevelopmentPlanController::class, 'show']);

/* ================= CERTIFICATES ================= */

Route::post('/achievement-cert', [AchievementCertController::class, 'store']);
Route::put('/achievement-cert/{id}', [AchievementCertController::class, 'update']);
Route::delete('/achievement-cert/{id}', [AchievementCertController::class, 'destroy']);

Route::post('/attainment-cert', [AttainmentCertController::class, 'store']);
Route::put('/attainment-cert/{id}', [AttainmentCertController::class, 'update']);
Route::delete('/attainment-cert/{id}', [AttainmentCertController::class, 'destroy']);

/* ================= COMPETENCY ================= */

Route::get('/competency-indicators', [CompetencyIndicatorController::class, 'index']);

Route::get('/users/{user}/competency-entries', [CompetencyEntryController::class, 'index']);
Route::post('/users/{user}/competency-entries', [CompetencyEntryController::class, 'store']);
Route::get('/users/{user}/competency-entries/{entry}', [CompetencyEntryController::class, 'show']);
Route::put('/users/{user}/competency-entries/{entry}', [CompetencyEntryController::class, 'update']);
Route::delete('/users/{user}/competency-entries/{entry}', [CompetencyEntryController::class, 'destroy']);

/* ================= COMPETENCY FEEDBACK ================= */

Route::get('/competency-entries/{entry}/feedback', [CompetencyFeedbackController::class, 'index']);
Route::post('/competency-entries/{entry}/feedback', [CompetencyFeedbackController::class, 'store']);

/* ================= STAFF ================= */

Route::get('/staff/my-students', [MentorStudentMappingController::class, 'index']);