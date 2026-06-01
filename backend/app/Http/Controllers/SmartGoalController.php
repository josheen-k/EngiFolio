<?php

namespace App\Http\Controllers;

use App\Models\GoalActionStep;
use App\Models\SmartGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SmartGoalController extends Controller
{
    private function goalsQueryForProfile(int $profileId)
    {
        return SmartGoal::with(['actionSteps', 'feedback', 'status'])
            ->where('profile_id', $profileId);
    }

    private function findGoalForProfileOrFail(int $goalId, int $profileId)
    {
        return $this->goalsQueryForProfile($profileId)->findOrFail($goalId);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'profile_id' => 'required|integer|exists:student_profiles,profile_id',
        ]);

        $query = $this->goalsQueryForProfile((int) $validated['profile_id']);

        if ($request->from) {
            $query->whereDate('start_date', '>=', $request->from);
        }

        if ($request->to) {
            $query->whereDate('start_date', '<=', $request->to);
        }

        // Keep persisted manual order first; fall back to newest records when order ties.
        $smartGoals = $query
            ->orderBy('goal_order')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($smartGoals);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'profile_id' => 'required|integer|exists:student_profiles,profile_id',
            'plan_id' => 'nullable|integer|exists:career_development_plans,plan_id',
            'goal_description' => 'required|string',
            'timeline' => 'nullable|string',
            'progress_notes' => 'nullable|string',
            'learnings' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'completion_date' => 'nullable|date',
            'completion_notes' => 'nullable|string',
            'goal_status_id' => 'required|integer|exists:goal_statuses,goal_status_id',
        ]);

        if (! empty($validated['plan_id'])) {
            $planOk = DB::table('career_development_plans')
                ->where('plan_id', $validated['plan_id'])
                ->where('profile_id', $validated['profile_id'])
                ->exists();

            if (! $planOk) {
                return response()->json(['message' => 'Plan does not belong to the specified profile'], 422);
            }
        }

        // New goals are inserted at the top by shifting existing goals down by one.
        $smartGoal = DB::transaction(function () use ($validated) {
            $profileId = (int) $validated['profile_id'];
            $planId = isset($validated['plan_id']) ? (int) $validated['plan_id'] : null;

            SmartGoal::where('profile_id', $profileId)->increment('goal_order');

            $goal = SmartGoal::create([
                'profile_id' => $profileId,
                'goal_description' => $validated['goal_description'],
                'timeline' => $validated['timeline'] ?? null,
                'progress_notes' => $validated['progress_notes'] ?? null,
                'learnings' => $validated['learnings'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'completion_date' => $validated['completion_date'] ?? null,
                'completion_notes' => $validated['completion_notes'] ?? null,
                'goal_status_id' => $validated['goal_status_id'],
                'goal_order' => 1,
            ]);

            if ($planId) {
                $goal->plans()->attach($planId);
            }

            return $goal;
        });

        return response()->json($smartGoal, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $validated = $request->validate([
            'profile_id' => 'required|integer|exists:student_profiles,profile_id',
        ]);

        $smartGoal = $this->findGoalForProfileOrFail((int) $id, (int) $validated['profile_id']);

        return response()->json($smartGoal);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'profile_id' => 'required|integer|exists:student_profiles,profile_id',
            'goal_description' => 'sometimes|required|string',
            'timeline' => 'nullable|string',
            'progress_notes' => 'nullable|string',
            'learnings' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'completion_date' => 'nullable|date',
            'completion_notes' => 'nullable|string',
            'goal_status_id' => 'sometimes|required|integer|exists:goal_statuses,goal_status_id',
        ]);

        $smartGoal = $this->findGoalForProfileOrFail((int) $id, (int) $validated['profile_id']);

        unset($validated['profile_id']);
        $smartGoal->update($validated);
        $smartGoal->load('status');

        return response()->json($smartGoal);
    }

    public function replaceActionSteps(Request $request, $goalId)
    {
        $validated = $request->validate([
            'profile_id' => 'required|integer|exists:student_profiles,profile_id',
            'steps' => 'required|array',
            'steps.*.step_description' => 'required|string',
            'steps.*.is_completed' => 'sometimes|boolean',
        ]);

        $smartGoal = $this->findGoalForProfileOrFail((int) $goalId, (int) $validated['profile_id']);

        DB::transaction(function () use ($smartGoal, $validated) {
            $smartGoal->actionSteps()->delete();

            foreach ($validated['steps'] as $index => $step) {
                GoalActionStep::create([
                    'goal_id' => $smartGoal->goal_id,
                    'step_description' => $step['step_description'],
                    'step_order' => $index + 1,
                    'is_completed' => (bool) ($step['is_completed'] ?? false),
                ]);
            }
        });

        return response()->json(
            $smartGoal->fresh(['actionSteps'])
        );
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'profile_id' => 'required|integer|exists:student_profiles,profile_id',
            'goal_ids' => 'required|array|min:1',
            'goal_ids.*' => 'required|integer|distinct|exists:smart_goals,goal_id',
        ]);

        $ownedGoalIds = $this->goalsQueryForProfile((int) $validated['profile_id'])
            ->whereIn('goal_id', $validated['goal_ids'])
            ->pluck('goal_id')
            ->all();

        if (count($ownedGoalIds) !== count($validated['goal_ids'])) {
            return response()->json(['message' => 'One or more goals do not belong to this profile'], 422);
        }

        // Persist the exact order received from the drag-and-drop UI.
        DB::transaction(function () use ($validated) {
            foreach ($validated['goal_ids'] as $index => $goalId) {
                SmartGoal::where('goal_id', $goalId)->update([
                    'goal_order' => $index + 1,
                ]);
            }
        });

        return response()->json([
            'message' => 'Goal order updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $validated = $request->validate([
            'profile_id' => 'required|integer|exists:student_profiles,profile_id',
        ]);

        $smartGoal = $this->findGoalForProfileOrFail((int) $id, (int) $validated['profile_id']);
        $smartGoal->delete();

        return response()->json([
            'message' => 'Smart goal deleted successfully',
        ]);
    }

    public function showUserGoals($profileId)
    {
        // Return user goals in the same persisted order used by the main goals list.
        $goals = SmartGoal::with(['actionSteps', 'feedback', 'status'])
            ->where('profile_id', $profileId)
            ->get();

        if ($goals->isEmpty()) {
            return response()->json(['message' => 'No goals for this user found'], 404);
        }

        return response()->json($goals);
    }
}
