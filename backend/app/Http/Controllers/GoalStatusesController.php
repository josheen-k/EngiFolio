<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GoalStatusesController extends Controller
{
    /**
     * Display a listing of all statuses
     */
    public function index()
    {
        $statuses = GoalStatus::all();
        return response()->json($statuses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|string|max:25|unique:goal_statuses,status'
        ]);

        $status = GoalStatus::create($validated);
        return response()->json($status, 201);
    }

    public function update(Request $request, GoalStatus $status)
    {
        $validated = $request->validate([
            // Should ignore current goal status when checking for unique
            'status' => 'required|string|max:25|unique:goal_statuses,status,' . $status->goal_status_id . ',goal_status_id'
        ]);

        $status->update($validated);
        return response()->json($status, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GoalStatus $status)
    {
        $status->delete();

        return response()->json(['message' => 'Status deleted successfully'], 200);
    }
}
