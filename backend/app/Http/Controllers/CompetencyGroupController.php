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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompetencyGroup $competencyGroup)
    {
        //
    }

    // Gets student competencies
    public function getStudentCompetencies($profileId)
    {
    $data = CompetencyGroup::with([
        'indicators.entries' => function ($query) use ($profileId) {
            $query->where('profile_id', $profileId)->with('entryLevel', 'competencyFeedback.staff', 'competencyEvidence.type');
        }
    ])->get();

    return response()->json($data);
    }
}
