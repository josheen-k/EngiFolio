<?php

namespace App\Http\Controllers;

use App\Models\GoalActionStep;
use App\Models\SmartGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SmartGoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SmartGoal::with(['actionSteps', 'feedback']);

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
            'plan_id' => 'required|integer',
            'goal_description' => 'required|string',
            'timeline' => 'nullable|string',
            'progress_notes' => 'nullable|string',
            'learnings' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'completion_date' => 'nullable|date',
            'completion_notes' => 'nullable|string',
            'status' => 'required|in:planned,in_progress,completed,on_hold',
        ]);

        // New goals are inserted at the top by shifting existing goals down by one.
        $smartGoal = DB::transaction(function () use ($validated) {
            SmartGoal::where('plan_id', $validated['plan_id'])
                ->increment('goal_order');

            $validated['goal_order'] = 1;
            return SmartGoal::create($validated);
        });

        return response()->json($smartGoal, 201);
    }

    /**
     * Display the specified resource.
     */
        public function show($id)
    {
        $smartGoal = SmartGoal::with(['actionSteps', 'feedback'])->findOrFail($id);

        return response()->json($smartGoal);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $smartGoal = SmartGoal::findOrFail($id);

        $validated = $request->validate([
            'plan_id' => 'sometimes|required|integer',
            'goal_description' => 'sometimes|required|string',
            'timeline' => 'nullable|string',
            'progress_notes' => 'nullable|string',
            'learnings' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'completion_date' => 'nullable|date',
            'completion_notes' => 'nullable|string',
            'status' => 'sometimes|required|in:planned,in_progress,completed,on_hold',
        ]);

        $smartGoal->update($validated);

        return response()->json($smartGoal);
    }

    public function replaceActionSteps(Request $request, $goalId)
    {
        $smartGoal = SmartGoal::findOrFail($goalId);

        $validated = $request->validate([
            'steps' => 'required|array',
            'steps.*.step_description' => 'required|string',
        ]);

        DB::transaction(function () use ($smartGoal, $validated) {
            $smartGoal->actionSteps()->delete();

            foreach ($validated['steps'] as $index => $step) {
                GoalActionStep::create([
                    'goal_id' => $smartGoal->goal_id,
                    'step_description' => $step['step_description'],
                    'step_order' => $index + 1,
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
            'goal_ids' => 'required|array|min:1',
            'goal_ids.*' => 'required|integer|distinct|exists:smart_goals,goal_id',
        ]);

        // Persist the exact order received from the drag-and-drop UI.
        DB::transaction(function () use ($validated) {
            foreach ($validated['goal_ids'] as $index => $goalId) {
                SmartGoal::where('goal_id', $goalId)->update([
                    'goal_order' => $index + 1,
                ]);
            }
        });

        return response()->json([
            'message' => 'Goal order updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $smartGoal = SmartGoal::findOrFail($id);
        $smartGoal->delete();

        return response()->json([
            'message' => 'Smart goal deleted successfully'
        ]);
    }

    public function showUserGoals($userId)
    {
        // Return user goals in the same persisted order used by the main goals list.
        $goals = SmartGoal::with(['actionSteps', 'feedback'])->whereHas('plan', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->get();

        if ($goals->isEmpty()) {
            return response()->json(['message' => 'No goals for this user found'], 404);
        }

        return response()->json($goals);
    }
}
