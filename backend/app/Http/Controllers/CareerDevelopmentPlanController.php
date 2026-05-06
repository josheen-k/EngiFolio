<?php

namespace App\Http\Controllers;

use App\Models\CareerDevelopmentPlan;
use Illuminate\Http\Request;

class CareerDevelopmentPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CareerDevelopmentPlan::query();

        if ($request->filled('profile_id')) {
            $validated = $request->validate([
                'profile_id' => 'required|integer|exists:student_profiles,profile_id',
            ]);
            $query->where('profile_id', $validated['profile_id']);
        }

        $plans = $query->get();
        return response()->json($plans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Return plan with all attached smart goals
        $plan = CareerDevelopmentPlan::with(['smartGoals.actionSteps'])->where('user_id', $id)->findOrFail();
        
        return response()->json($plan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CareerDevelopmentPlan $careerDevelopmentPlan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CareerDevelopmentPlan $careerDevelopmentPlan)
    {
        //
    }
}
