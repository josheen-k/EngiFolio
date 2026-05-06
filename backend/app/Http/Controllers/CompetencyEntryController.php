<?php

namespace App\Http\Controllers;

use App\Models\CompetencyEntry;
use Illuminate\Http\Request;

class CompetencyEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($profileId)
    {
        $entries = CompetencyEntry::with('indicator', 'entryLevel')->where('profile_id', $profileId)->get();

        if ($entries->isEmpty()) {
            return response()->json(['message' => 'No comptencies for this user found'], 404);
        }

        return response()->json($entries);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'profile_id' => 'required|exists:student_profiles,profile_id',
            'indicator_id'   => 'required|exists:competency_indicators,indicator_id',
            'experience_title'     => 'required|string|max:255',
            'associated_year'     => 'required|integer',
            'experience_tasks'   => 'required|string',
            'key_learnings'   => 'nullable|string',
            'future_applications'   => 'nullable|string',
            'entry_level_id'   => 'required|integer|exists:competency_entry_levels,entry_level_id',
            'entry_status_id'   => 'required|integer|exists:competency_entry_statuses,entry_status_id',
            'start_date'   => 'required|date',
            'end_date'   => 'nullable|date',
        ]);

        $entry = CompetencyEntry::create($validated);
        
        return response()->json($entry, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $competencyEntryId)
    {
        // Fails if no entry is found
        $entry = \App\Models\CompetencyEntry::findOrFail($competencyEntryId);

        // Validate all data coming in
        $validated = $request->validate([
            'profile_id' => 'required|exists:student_profiles,profile_id',
            'indicator_id'   => 'required|exists:competency_indicators,indicator_id',
            'experience_title'     => 'required|string|max:255',
            'associated_year'     => 'required|integer',
            'experience_tasks'   => 'required|string',
            'key_learnings'   => 'nullable|string',
            'future_applications'   => 'nullable|string',
            'entry_level_id'   => 'required|integer|exists:competency_entry_levels,entry_level_id',
            'entry_status_id'   => 'required|integer|exists:competency_entry_statuses,entry_status_id',
            'start_date'   => 'required|date',
            'end_date'   => 'nullable|date',
        ]);

        // Update entry with validated data
        $entry->update($validated);

        return response()->json(['message' => 'Entry updated successfully', 'entry' => $entry]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($competencyEntryId)
    {
        $entry  = CompetencyEntry::findOrFail($competencyEntryId);
        $entry->delete();


        return response()->json(['message' => 'Competency entry successfully deleted'], 200);
    }
}
