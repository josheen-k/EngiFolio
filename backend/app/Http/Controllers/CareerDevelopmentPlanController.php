<?php

namespace App\Http\Controllers;

use App\Models\CareerDevelopmentPlan;
use App\Models\SmartGoal;
use Illuminate\Http\Request;

class CareerDevelopmentPlanController extends Controller
{
    private function planWithGoals(CareerDevelopmentPlan $plan): CareerDevelopmentPlan
    {
        return $plan->load([
            'smartGoals.actionSteps',
            'smartGoals.status',
        ]);
    }

    private function validatePlanPayload(Request $request, ?CareerDevelopmentPlan $plan = null): array
    {
        $profileId = (int) $request->input('profile_id', $plan?->profile_id);

        return $request->validate([
            'profile_id' => [
                $plan ? 'sometimes' : 'required',
                'integer',
                'exists:student_profiles,profile_id',
            ],
            'plan_year' => [
                $plan ? 'sometimes' : 'required',
                'integer',
                'min:1',
            ],
            'professional_interests' => 'nullable|string',
            'employers_of_interest' => 'nullable|string',
            'networking_plan' => 'nullable|string',
            'personal_values' => 'nullable|string',
            'extracurriculars' => 'nullable|string',
            'development_focus' => 'nullable|string',
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CareerDevelopmentPlan::with([
            'smartGoals.actionSteps',
            'smartGoals.status',
        ]);

        if ($request->filled('profile_id')) {
            $validated = $request->validate([
                'profile_id' => 'required|integer|exists:student_profiles,profile_id',
            ]);
            $query->where('profile_id', $validated['profile_id']);
        }

        $plans = $query
            ->orderBy('plan_year')
            ->orderBy('created_at')
            ->get();

        return response()->json($plans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validatePlanPayload($request);

        $plan = CareerDevelopmentPlan::create($validated);

        return response()->json($this->planWithGoals($plan), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($profile_id)
    {
        try {
            $plans = CareerDevelopmentPlan::with([
                'smartGoals.actionSteps',
                'smartGoals.status',
            ])
                ->where('profile_id', $profile_id)
                ->orderBy('plan_year')
                ->orderBy('created_at')
                ->get();

            return response()->json($plans);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CareerDevelopmentPlan $plan)
    {
        $validated = $this->validatePlanPayload($request, $plan);

        if (isset($validated['profile_id']) && (int) $validated['profile_id'] !== (int) $plan->profile_id) {
            return response()->json(['message' => 'Cannot move a plan to a different profile'], 422);
        }

        unset($validated['profile_id']);
        $plan->update($validated);

        return response()->json($this->planWithGoals($plan));
    }

    public function linkSmartGoals(Request $request, CareerDevelopmentPlan $plan)
    {
        $validated = $request->validate([
            'profile_id' => 'required|integer|exists:student_profiles,profile_id',
            // present|array allows zero linked goals; required rejects an empty [].
            'goal_ids' => 'present|array',
            'goal_ids.*' => 'integer|distinct|exists:smart_goals,goal_id',
        ]);

        if ((int) $validated['profile_id'] !== (int) $plan->profile_id) {
            return response()->json(['message' => 'Plan does not belong to the specified profile'], 422);
        }

        $ownedGoalIds = SmartGoal::where('profile_id', $validated['profile_id'])
            ->whereIn('goal_id', $validated['goal_ids'])
            ->pluck('goal_id')
            ->all();

        if (count($validated['goal_ids']) > 0 && count($ownedGoalIds) !== count($validated['goal_ids'])) {
            return response()->json(['message' => 'One or more goals do not belong to this profile'], 422);
        }

        $plan->smartGoals()->sync($validated['goal_ids']);

        return response()->json($this->planWithGoals($plan->fresh()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CareerDevelopmentPlan $plan)
    {
        $plan->delete();

        return response()->json([
            'message' => 'Career development plan deleted successfully',
        ]);
    }
}
