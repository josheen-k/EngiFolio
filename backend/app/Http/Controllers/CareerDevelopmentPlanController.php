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
public function show($profile_id)
{
    try {
        $plans = CareerDevelopmentPlan::with([
            'smartGoals.actionSteps', 
            'smartGoals.status'
        ])
        ->where('profile_id', $profile_id)
        ->get(); // Use get() to return a collection of plans

        return response()->json($plans);
    } catch (\Exception $e) {
        // If it still fails, this will tell you exactly why (e.g. "Column not found")
        return response()->json(['error' => $e.getMessage()], 500);
    }
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
