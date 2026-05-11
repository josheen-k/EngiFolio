<?php

namespace App\Http\Controllers;

use App\Models\CompetencyIndicator;
use App\Models\CompetencyEntry;
use Illuminate\Http\Request;

class CompetencyIndicatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $indicators = CompetencyIndicator::all();

        return response()->json($indicators);
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
    public function show($profileId)
    {
        $indicators = CompetencyIndicator::with(['entries' => function($query) use ($profileId) {
            $query->where('profile_id', $profileId);
        }])->get();

        return response()->json($indicators);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompetencyIndicator $competencyIndicator)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompetencyIndicator $competencyIndicator)
    {
        //
    }

    // Retrieves all competencies and count of how many entries the student has for each and highest level
    public function competenciesWithHighest($profileId)
    {
        // Count the amount of entries the student has for each competency
        $indicators = CompetencyIndicator::withCount(['entries' => function ($query) use ($profileId) {
            $query->where('profile_id', $profileId);}])
        // Call function that calculates the highest entry by level weighting
        ->with(['highestEntry' => function ($query) use ($profileId) {
            $query->where('profile_id', $profileId);
        }])->get();

        return response()->json($indicators);
    }
}
