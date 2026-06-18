<?php

namespace App\Http\Controllers;

use App\Models\CompetencyGroup;
use Illuminate\Http\Request;

class CompetencyGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = CompetencyGroup::get(); 

        return response()->json($groups);    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'display_id'        => 'required|string|max:20|unique:competency_groups,display_id',
            'group_name'        => 'required|string|max:100',
            'description'       => 'nullable|string',
            'discontinued_date' => 'nullable|date',
        ]);

        $group = CompetencyGroup::create($validated);
        return response()->json($group, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CompetencyGroup $competencyGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompetencyGroup $competencyGroup)
    {
        //
        $validated = $request->validate([
            'display_id'        => 'sometimes|required|string|max:20|unique:competency_groups,display_id,' . $competencyGroup->group_id . ',group_id',
            'group_name'        => 'sometimes|required|string|max:100',
            'description'       => 'nullable|string',
            'discontinued_date' => 'nullable|date',
        ]);

        $competencyGroup->update($validated);
        return response()->json($competencyGroup);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompetencyGroup $competencyGroup)
    {
        //
        $competencyGroup->delete();
        return response()->json(['message' => 'Group deleted successfully']);
    }

    // Gets student competencies
    public function getStudentCompetencies($profileId)
    {
    $studentComp = CompetencyGroup::with([
        'indicators.attainmentIndicators',
        'indicators.entries' => function ($query) use ($profileId) {
            $query->where('profile_id', $profileId)->with('entryLevel', 'competencyFeedback.staff', 'competencyEvidence');
        }
    ])->get();

    return response()->json($studentComp);
    }
}
