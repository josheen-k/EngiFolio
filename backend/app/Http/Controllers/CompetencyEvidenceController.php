<?php

namespace App\Http\Controllers;

use App\Models\CompetencyEvidence;
use Illuminate\Http\Request;

class CompetencyEvidenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = CompetencyEvidence::get(); 

        return response()->json($groups);    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'entry_id'       => 'required|exists:competency_entries,entry_id',
            'evidence_type'  => 'required|string|max:15',
            'evidence_value' => 'required|string|max:500',
        ]);

        $evidence = CompetencyEvidence::create($validated);

        return response()->json($evidence, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CompetencyEvidence $CompetencyEvidence)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompetencyEvidence $CompetencyEvidence)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($CompetencyEvidenceId)
    {
        $entry  = CompetencyEvidence::findOrFail($CompetencyEvidenceId);
        $entry->delete();

        return response()->json(['message' => 'Competency evidence successfully deleted'], 200);
    }
}

