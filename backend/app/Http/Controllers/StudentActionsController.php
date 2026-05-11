<?php

namespace App\Http\Controllers;

use App\Models\StudentAction;
use Illuminate\Http\Request;

class StudentActionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($profileId)
    {
        $actions = StudentAction::where('student_profile_id', $profileId)->get();

        if ($actions->isEmpty()) {
            return response()->json(['message' => 'No actions for this user found'], 404);
        }

        return response()->json($actions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Make sure request fits the database constraints
        $validated = $request->validate([
            'action' => 'required|string|max:100',
            'student_profile_id' => 'required|exists:student_profiles,profile_id',
        ]);

        $status = StudentAction::create($validated);
        
        return response()->json($status, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {

    }

    // Retrieve the 5 most recent actions for a specific user
    public function getRecentActions($profileId) {
        $actions = StudentAction::where('student_profile_id', $profileId)->orderBy('created_at', 'desc')->limit(5)->get();

        return response()->json($actions);
    }
}

