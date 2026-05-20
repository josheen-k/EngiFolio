<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AchievementCertController;
use App\Http\Controllers\AttainmentCertController;
use App\Http\Controllers\CareerDevelopmentPlanController;
use App\Http\Controllers\CompetencyIndicatorController;
use App\Http\Controllers\StudentActionsController;
use App\Http\Controllers\CompetencyEntryLevelsController;
use App\Http\Controllers\CompetencyGroupController;
use App\Http\Controllers\GoalActionStepController;
use App\Http\Controllers\GoalStatusesController;
use App\Http\Controllers\IndustryContactController;
use App\Http\Controllers\NetworkingEventCommentController;
use App\Http\Controllers\NetworkingEventController;
use App\Http\Controllers\NetworkingEventQuestionController;
use App\Http\Controllers\SmartGoalController;
use App\Http\Controllers\StudentLinkController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompetencyEvidenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ElevatorPitchController;

use App\Http\Controllers\CompetencyEntryController;
use App\Http\Controllers\CompetencyFeedbackController;
use App\Http\Controllers\MentorStudentMappingController; 

use App\Http\Controllers\GoalFeedbackController;
use App\Http\Controllers\CdlModuleController;

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

/* ================= USERS ================= */

//  User Routes
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{user}', [UserController::class, 'show']);
Route::put('/users/{user}', [UserController::class, 'update']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);

// Admin user management routes
Route::get('/admin/users-overview', [AdminController::class, 'usersOverview']);
Route::get('/admin/users-overview/export-pdf', [AdminController::class, 'exportUsersOverviewPdf']);
Route::post('/admin/users', [AdminController::class, 'createUser']);
Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser']);

Route::get('/profile/{id}', [StudentProfileController::class, 'show']);
Route::put('/profile/{id}', [StudentProfileController::class, 'update']);
Route::get('/profileDash/{id}', [StudentProfileController::class, 'getDashboardInfo']);

Route::get('/users/{user}/industry-contacts', [IndustryContactController::class, 'index']);
Route::post('/users/{user}/industry-contacts', [IndustryContactController::class, 'store']);
Route::get('/users/{user}/industry-contacts/{industryContact}', [IndustryContactController::class, 'show']);
Route::put('/users/{user}/industry-contacts/{industryContact}', [IndustryContactController::class, 'update']);
Route::delete('/users/{user}/industry-contacts/{industryContact}', [IndustryContactController::class, 'destroy']);

/* ================= STUDENT LINKS ================= */

Route::post('/link', [StudentLinkController::class, 'store']);
Route::put('/link/{id}', [StudentLinkController::class, 'update']);
Route::delete('/link/{id}', [StudentLinkController::class, 'destroy']);

Route::get('/smart-goals', [SmartGoalController::class, 'index']);
Route::post('/smart-goals', [SmartGoalController::class, 'store']);
Route::put('/smart-goals/reorder', [SmartGoalController::class, 'reorder']);
Route::get('/smart-goals/{id}', [SmartGoalController::class, 'show']);
Route::put('/smart-goals/{id}', [SmartGoalController::class, 'update']);
Route::delete('/smart-goals/{id}', [SmartGoalController::class, 'destroy']);
Route::put('/smart-goals/{goalId}/action-steps', [SmartGoalController::class, 'replaceActionSteps']);
Route::get('user/smart-goals/{userId}', [SmartGoalController::class, 'showUserGoals']);
Route::post('/smart-goals/{goalId}/action-steps', [GoalActionStepController::class, 'store']);
Route::put('/action-steps/{stepId}', [GoalActionStepController::class, 'update']);
Route::delete('/action-steps/{stepId}', [GoalActionStepController::class, 'destroy']);

// Staff SMART Goal feedback routes
Route::get('/smart-goals/all/feedback/{staffID}', [GoalFeedbackController::class, 'index']);
Route::post('/smart-goals/{goalID}/feedback', [GoalFeedbackController::class, 'store']);
Route::get('/smart-goals/{goalID}/feedback', [GoalFeedbackController::class, 'show']);
Route::put('/smart-goals/{goalID}/feedback', [GoalFeedbackController::class, 'update']);
Route::delete('/smart-goals/{goalID}/feedback', [GoalFeedbackController::class, 'destroy']);
Route::get('/smart-goals/{goalID}/feedback/get', [GoalFeedbackController::class, 'getFeedbackByGoal']);

// // Mentor Student Mapping routes
// Route::get('/mapping/staff/{staffID}', [MentorStudentMappingController::class, 'getMappedStudents']);
// Route::get('/mapping/student/{profileID}', [MentorStudentMappingController::class, 'getMappedStaff']);
// Route::post('/mapping', [MentorStudentMappingController::class, 'store']);
// Route::put('/mapping/{mappingID}', [MentorStudentMappingController::class, 'update']);
// Route::put('/mapping/student/{profileID}', [MentorStudentMappingController::class, 'updateMappedStaff']);
// Route::delete('/mapping/{mappingID}', [MentorStudentMappingController::class, 'destroy']);
// Route::delete('/mapping/student/{profileID}', [MentorStudentMappingController::class, 'destroyMappedStaff']);

/* ================= CAREER PLAN ================= */

Route::get('/career-plans', [CareerDevelopmentPlanController::class, 'index']);
Route::post('/career-plans', [CareerDevelopmentPlanController::class, 'store']);
Route::put('/career-plans/{plan}/smart-goals', [CareerDevelopmentPlanController::class, 'linkSmartGoals']);
Route::get('/career-plans/{id}', [CareerDevelopmentPlanController::class, 'show']);
Route::put('/career-plans/{plan}', [CareerDevelopmentPlanController::class, 'update']);
Route::delete('/career-plans/{plan}', [CareerDevelopmentPlanController::class, 'destroy']);

// Competency Entries
Route::get('/competency-entries/{profile_id}', [CompetencyEntryController::class, 'index']);
Route::post('/competency-entries', [CompetencyEntryController::class, 'store']);
Route::put('competency-entries/{entry_id}', [CompetencyEntryController::class, 'update']);
Route::delete('competency-entries/{entry_id}', [CompetencyEntryController::class, 'destroy']);


// Competency Indicators
Route::get('/competency-indicators', [CompetencyIndicatorController::class, 'index']);
Route::get('/student-competency-indicators/{id}', [CompetencyIndicatorController::class, 'competenciesWithHighest']);
Route::get('/competency-indicators/{id}', [CompetencyIndicatorController::class, 'show']);

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

// Export profile data as pdf
Route::post('/profile/{id}/export-pdf', [StudentProfileController::class, 'exportPdf']);

// Achievement certificates
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
// Goal status
Route::get('/goal-status', [GoalStatusesController::class, 'index']);
Route::post('/goal-status', [GoalStatusesController::class, 'store']);
Route::put('/goal-status/{status}', [GoalStatusesController::class, 'update']);
Route::delete('/goal-status/{status}', [GoalStatusesController::class, 'destroy']);

// Student actions
Route::get('/student-actions/recent/{id}', [StudentActionsController::class, 'getRecentActions']);
Route::post('/student-actions/new', [StudentActionsController::class, 'store']);

// Competency Entry Levels
Route::get('/competency-levels', [CompetencyEntryLevelsController::class, 'index']);
Route::get('/competency-levels-by-weight/{weight}', [CompetencyEntryLevelsController::class, 'getLevelByWeighting']);

// Competency Groups
Route::get('/competency-groups-student/{id}', [CompetencyGroupController::class, 'getStudentCompetencies']);

// Competency Evidence
Route::post('/competency-evidence', [CompetencyEvidenceController::class, 'store']);
Route::delete('/competency-evidence/{id}', [CompetencyEvidenceController::class, 'destroy']);

//ElevatorPitch
Route::get('/profile/{profile}/elevator-pitch', [ElevatorPitchController::class, 'show']);
Route::post('/profile/{profile}/elevator-pitch', [ElevatorPitchController::class, 'store']);
Route::put('/profile/{profile}/elevator-pitch', [ElevatorPitchController::class, 'update']);

//CDL Page
Route::get('/cdl-modules', [CdlModuleController::class, 'index']);
