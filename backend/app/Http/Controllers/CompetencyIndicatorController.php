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
        $indicators = CompetencyIndicator::with('attainmentIndicators')->get();
        return response()->json($indicators);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'group_id'                => 'required|exists:competency_groups,group_id',
            'display_id'              => 'required|string|max:5',
            'indicator_name'          => 'required|string|max:255',
            'description'             => 'required|string',
            'indicator_link'          => 'nullable|url',
            'discontinued_date'       => 'nullable|date',
            'attainment_indicators'   => 'nullable|array',
            'attainment_indicators.*' => 'string',
        ]);

        $indicator = CompetencyIndicator::create($validated);

        foreach ($validated['attainment_indicators'] ?? [] as $text) {
            $indicator->attainmentIndicators()->create(['attainment_indicator' => $text]);
        }

        return response()->json($indicator->load('attainmentIndicators'), 201);
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
        $validated = $request->validate([
            'group_id'                => 'sometimes|required|exists:competency_groups,group_id',
            'display_id'              => 'sometimes|required|string|max:5',
            'indicator_name'          => 'sometimes|required|string|max:255',
            'description'             => 'sometimes|required|string',
            'indicator_link'          => 'nullable|url',
            'discontinued_date'       => 'nullable|date',
            'attainment_indicators'   => 'nullable|array',
            'attainment_indicators.*' => 'string',
        ]);

        $competencyIndicator->update($validated);

        if (array_key_exists('attainment_indicators', $validated)) {
            $competencyIndicator->attainmentIndicators()->delete();
            foreach ($validated['attainment_indicators'] as $text) {
                $competencyIndicator->attainmentIndicators()->create(['attainment_indicator' => $text]);
            }
        }

        return response()->json($competencyIndicator->load('attainmentIndicators'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompetencyIndicator $competencyIndicator)
    {
        //
        $competencyIndicator->attainmentIndicators()->delete();
        $competencyIndicator->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    // Retrieves all competencies and count of how many entries the student has for each and highest level
    public function competenciesWithHighest($profileId)
    {
        // Count the amount of entries the student has for each competency
        $indicators = CompetencyIndicator::whereNull('discontinued_date')->withCount(['entries' => function ($query) use ($profileId) {
            $query->where('profile_id', $profileId);}])
        // Call function that calculates the highest entry by level weighting
        ->with(['highestEntry' => function ($query) use ($profileId) {
            $query->where('profile_id', $profileId);
        }])->get();

        $sorted = $indicators->sortBy(function ($item) {
            return $item->highestEntry->competency_level_weighting ?? 0;}
        )->values();

        return response()->json($sorted);
    }
}
