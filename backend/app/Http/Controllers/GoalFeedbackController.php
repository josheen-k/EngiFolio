<?php

namespace App\Http\Controllers;

use App\Models\GoalFeedback;
use Illuminate\Http\Request;

class GoalFeedbackController extends Controller
{
    // Display a listing of the resource.
    public function index($goalID)
    {
        //
        return response()->json(
            GoalFeedback::where('goal_id','=', $goalID)->get()
        );
    }

    // Store a newly created resource in storage.
    public function store(Request $request, $goalID/*, $staffID*/)
    {
        
        $validated = $request->validate([
            'staff_id' => 'required|integer',
            'feedback_content' => 'required|string',
        ]);

        $goalFeedback = GoalFeedback::create([
            'goal_id' => $goalID,
            // 'staff_id' => $staffID,
            'staff_id' => $validated['staff_id'],
            'feedback_content' => $validated['feedback_content'],
        ]);

        return response()->json($goalFeedback, 201);
    }

    // Display the specified resource.
    public function show(/*GoalFeedback $goalFeedback*/ $goalID, $feedbackID)
    {
        // $goalFeedback = GoalFeedback::findOrFail($feedbackID);
        $goalFeedback = GoalFeedback::where('goal_id','=', $goalID)->where('feedback_id','=', $feedbackID)->get();
        if ($goalFeedback->isEmpty()) {
            return response()->json(['message' => 'Error: Corresponding goal or feedback not found'], 404);
        }

        return response()->json($goalFeedback);
    }

    // Update the specified resource in storage.
    public function update(Request $request/*, GoalFeedback $goalFeedback*/, $goalID, $feedbackID)
    {
        $goalFeedback = GoalFeedback::findOrFail($feedbackID);

        $validated = $request->validate([
            'feedback_content' => 'required|string',
        ]);

        $goalFeedback->update($validated);

        return response()->json($goalFeedback);
    }

    // Remove the specified resource from storage.
    public function destroy(GoalFeedback $goalFeedback, $goalID)
    {
        $goalFeedback->delete();
        return response()->json(['message' => 'Goal feedback deleted successfully']);
    }
}
