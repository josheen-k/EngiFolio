<?php

namespace App\Http\Controllers;

use App\Models\CompetencyEntry;
use App\Models\StudentProfile;
use Illuminate\Http\Request;

class CompetencyEntryController extends Controller
{
    public function index($userId)
    {
        $profile = StudentProfile::where('user_id', $userId)->first();

        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $entries = CompetencyEntry::with('indicator')
            ->where('profile_id', $profile->profile_id)
            ->get();

        return response()->json($entries);
    }

    public function store(Request $request, $userId)
    {
        $profile = StudentProfile::where('user_id', $userId)->first();

        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $validated = $request->validate([
            'indicator_id' => 'required|integer',
            'experience_title' => 'required|string|max:255',
            'associated_year' => 'required|integer',
            'experience_tasks' => 'required|string',
            'key_learnings' => 'nullable|string',
            'future_applications' => 'nullable|string',
            'level' => 'required|string',
            'status' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
        ]);

        $validated['profile_id'] = $profile->profile_id;

        $entry = CompetencyEntry::create($validated);

        return response()->json($entry, 201);
    }

    public function show($userId, $entryId)
    {
        $profile = StudentProfile::where('user_id', $userId)->first();

        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $entry = CompetencyEntry::with('indicator')
            ->where('profile_id', $profile->profile_id)
            ->where('entry_id', $entryId)
            ->first();

        if (!$entry) {
            return response()->json(['message' => 'Competency entry not found'], 404);
        }

        return response()->json($entry);
    }

    public function update(Request $request, $userId, $entryId)
    {
        $profile = StudentProfile::where('user_id', $userId)->first();

        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $entry = CompetencyEntry::where('profile_id', $profile->profile_id)
            ->where('entry_id', $entryId)
            ->first();

        if (!$entry) {
            return response()->json(['message' => 'Competency entry not found'], 404);
        }

        $validated = $request->validate([
            'indicator_id' => 'sometimes|required|integer',
            'experience_title' => 'sometimes|required|string|max:255',
            'associated_year' => 'sometimes|required|integer',
            'experience_tasks' => 'sometimes|required|string',
            'key_learnings' => 'nullable|string',
            'future_applications' => 'nullable|string',
            'level' => 'sometimes|required|string',
            'status' => 'sometimes|required|string',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'nullable|date',
        ]);

        $entry->update($validated);

        return response()->json($entry);
    }

    public function destroy($userId, $entryId)
    {
        $profile = StudentProfile::where('user_id', $userId)->first();

        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $entry = CompetencyEntry::where('profile_id', $profile->profile_id)
            ->where('entry_id', $entryId)
            ->first();

        if (!$entry) {
            return response()->json(['message' => 'Competency entry not found'], 404);
        }

        $entry->delete();

        return response()->json(['message' => 'Competency entry deleted']);
    }
}