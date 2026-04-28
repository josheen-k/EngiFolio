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
        $query = SmartGoal::with(['actionSteps', 'feedback', 'status']);

        if ($request->from) {
            $query->whereDate('start_date', '>=', $request->from);
        }

        if ($request->to) {
            $query->whereDate('start_date', '<=', $request->to);
        }

        $smartGoals = $query->orderBy('created_at', 'desc')->get();

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
            'goal_status_id' => 'required|integer|exists:goal_statuses,goal_status_id',
        ]);

        $smartGoal = SmartGoal::create($validated);

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

    public function showUserGoals($profileId)
    {
        $goals = SmartGoal::with(['actionSteps', 'feedback', 'status'])
                ->whereHas('plan', fn($q) => $q->where('profile_id', $profileId))
                ->get();

        return response()->json($goals);
    }
}
