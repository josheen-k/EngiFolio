<?php

namespace App\Http\Controllers;

use App\Models\GoalActionStep;
use Illuminate\Http\Request;

class GoalActionStepController extends Controller
{
    private function findStepForProfileOrFail(int $stepId, int $profileId): GoalActionStep
    {
        $step = GoalActionStep::with('smartGoal')->findOrFail($stepId);

        if (!$step->smartGoal || (int) $step->smartGoal->profile_id !== $profileId) {
            abort(404);
        }

        return $step;
    }

    public function store(Request $request, $goalId)
    {
        $validated = $request->validate([
            'step_description' => 'required|string',
            'step_order' => 'nullable|integer',
            'is_completed' => 'sometimes|boolean',
        ]);

        $step = GoalActionStep::create([
            'goal_id' => $goalId,
            'step_description' => $validated['step_description'],
            'step_order' => $validated['step_order'] ?? 0,
            'is_completed' => (bool) ($validated['is_completed'] ?? false),
        ]);

        return response()->json($step, 201);
    }

    public function update(Request $request, $stepId)
    {
        $validated = $request->validate([
            'profile_id' => 'required|integer|exists:student_profiles,profile_id',
            'step_description' => 'sometimes|string',
            'step_order' => 'nullable|integer',
            'is_completed' => 'sometimes|boolean',
        ]);

        if (!array_key_exists('step_description', $validated)
            && !array_key_exists('is_completed', $validated)
            && !array_key_exists('step_order', $validated)) {
            return response()->json([
                'message' => 'No updatable fields provided.',
            ], 422);
        }

        $step = $this->findStepForProfileOrFail((int) $stepId, (int) $validated['profile_id']);

        $updates = [];
        if (array_key_exists('step_description', $validated)) {
            $updates['step_description'] = $validated['step_description'];
        }
        if (array_key_exists('step_order', $validated)) {
            $updates['step_order'] = $validated['step_order'];
        }
        if (array_key_exists('is_completed', $validated)) {
            $updates['is_completed'] = $validated['is_completed'];
        }

        $step->update($updates);

        return response()->json($step->fresh());
    }

    public function destroy(Request $request, $stepId)
    {
        $validated = $request->validate([
            'profile_id' => 'required|integer|exists:student_profiles,profile_id',
        ]);

        $step = $this->findStepForProfileOrFail((int) $stepId, (int) $validated['profile_id']);
        $step->delete();

        return response()->json([
            'message' => 'Action step deleted successfully',
        ]);
    }
}
