<?php

namespace App\Http\Controllers;

use App\Models\GoalActionStep;
use Illuminate\Http\Request;

class GoalActionStepController extends Controller
{
    public function store(Request $request, $goalId)
    {
        $validated = $request->validate([
            'step_description' => 'required|string',
            'step_order' => 'nullable|integer',
        ]);

        $step = GoalActionStep::create([
            'goal_id' => $goalId,
            'step_description' => $validated['step_description'],
            'step_order' => $validated['step_order'] ?? 0,
        ]);

        return response()->json($step, 201);
    }

    public function update(Request $request, $stepId)
    {
        $step = GoalActionStep::findOrFail($stepId);

        $validated = $request->validate([
            'step_description' => 'required|string',
            'step_order' => 'nullable|integer',
        ]);

        $step->update([
            'step_description' => $validated['step_description'],
            'step_order' => $validated['step_order'] ?? $step->step_order,
        ]);

        return response()->json($step);
    }

    public function destroy($stepId)
    {
        $step = GoalActionStep::findOrFail($stepId);
        $step->delete();

        return response()->json([
            'message' => 'Action step deleted successfully'
        ]);
    }
}
