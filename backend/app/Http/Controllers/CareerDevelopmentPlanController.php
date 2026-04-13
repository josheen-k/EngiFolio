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
        // Get current user's career development plans
        // For now, without auth, we'll return all plans (you may need to add auth later)
        $plans = CareerDevelopmentPlan::all();
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
    public function show(CareerDevelopmentPlan $careerDevelopmentPlan)
    {
        //
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
