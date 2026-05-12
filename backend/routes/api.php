<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AchievementCertController;
use App\Http\Controllers\AttainmentCertController;
use App\Http\Controllers\CareerDevelopmentPlanController;
use App\Http\Controllers\CompetencyEntryController;
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
use App\Http\Controllers\GoalFeedbackController;

//  User Routes
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{user}', [UserController::class, 'show']);
Route::put('/users/{user}', [UserController::class, 'update']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);

// Admin user management routes
Route::get('/admin/users-overview', [AdminController::class, 'usersOverview']);
Route::post('/admin/users', [AdminController::class, 'createUser']);
Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser']);

// Student Profile
Route::get('/profile/{id}', [StudentProfileController::class, 'show']);
Route::put('/profile/{id}', [StudentProfileController::class, 'update']);
Route::get('/profileDash/{id}', [StudentProfileController::class, 'getDashboardInfo']);

// Industry contacts/networking pages
Route::get('/users/{profile}/industry-contacts', [IndustryContactController::class, 'index']);
Route::post('/users/{profile}/industry-contacts', [IndustryContactController::class, 'store']);
Route::get('/users/{profile}/industry-contacts/{industryContact}', [IndustryContactController::class, 'show']);
Route::put('/users/{profile}/industry-contacts/{industryContact}', [IndustryContactController::class, 'update']);
Route::delete('/users/{profile}/industry-contacts/{industryContact}', [IndustryContactController::class, 'destroy']);

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
Route::get('/user/smart-goals/{userId}', [SmartGoalController::class, 'showUserGoals']);
Route::post('/smart-goals/{goalId}/action-steps', [GoalActionStepController::class, 'store']);
Route::put('/action-steps/{stepId}', [GoalActionStepController::class, 'update']);
Route::delete('/action-steps/{stepId}', [GoalActionStepController::class, 'destroy']);

// Staff SMART Goal feedback routes
Route::get('/smart-goals/all/feedback', [GoalFeedbackController::class, 'index']);
Route::post('/smart-goals/{goalID}/feedback', [GoalFeedbackController::class, 'store']);
Route::get('/smart-goals/{goalID}/feedback', [GoalFeedbackController::class, 'show']);
Route::put('/smart-goals/{goalID}/feedback', [GoalFeedbackController::class, 'update']);
Route::delete('/smart-goals/{goalID}/feedback', [GoalFeedbackController::class, 'destroy']);

// Career Development Plan routes
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

// Attainment certificates
Route::post('/attainment-cert', [AttainmentCertController::class, 'store']);
Route::put('/attainment-cert/{id}', [AttainmentCertController::class, 'update']);
Route::delete('/attainment-cert/{id}', [AttainmentCertController::class, 'destroy']);

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

// Competency Links
Route::post('/competency-evidence', [CompetencyEvidenceController::class, 'store']);
Route::delete('/competency-evidence/{id}', [CompetencyEvidenceController::class, 'destroy']);

//ElevatorPitch
Route::get('/profile/{profile}/elevator-pitch', [ElevatorPitchController::class, 'show']);
Route::post('/profile/{profile}/elevator-pitch', [ElevatorPitchController::class, 'store']);
Route::put('/profile/{profile}/elevator-pitch', [ElevatorPitchController::class, 'update']);