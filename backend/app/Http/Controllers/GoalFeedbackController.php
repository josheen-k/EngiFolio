<?php

namespace App\Http\Controllers;

use App\Models\GoalFeedback;
use Illuminate\Http\Request;

class GoalFeedbackController extends Controller
{
    // return feedback by goal_id
    public function getFeedbackByGoal($goalID)
    {
        return response()->json(GoalFeedback::where('goal_id', $goalID)->get());
    }

    // Display a listing of the resource.
    public function index($staffID)
    {
        return response()->json(GoalFeedback::where('staff_id','=', $staffID)->get());
        // return response()->json(GoalFeedback::all());
    }

    // Store a newly created resource in storage.
    public function store(Request $request, $goalID/*, $staffID*/)
    {
        // ensure feedback does not already exist for this goal
        $goalFeedbackCheck = GoalFeedback::where('goal_id','=', $goalID)->get();
        if (!$goalFeedbackCheck->isEmpty()) {
            return response()->json(['message' => 'Error: Feedback already exists', $goalFeedbackCheck]);
        }

        $validated = $request->validate([
            'staff_id' => 'required|integer',
            'feedback_content' => 'required|string',
        ]);

        $goalFeedback = GoalFeedback::create([
            'goal_id' => $goalID,
            'staff_id' => $validated['staff_id'],
            'feedback_content' => $validated['feedback_content'],
        ]);

        return response()->json($goalFeedback, 201);
    }

    // Display the specified resource.
    public function show(/*GoalFeedback $goalFeedback*/ $goalID/*, $feedbackID*/)
    {
        // $goalFeedback = GoalFeedback::findOrFail($feedbackID);

        // check feedback with goal id exists
        $goalFeedback = GoalFeedback::where('goal_id','=', $goalID)->get();
        if ($goalFeedback->isEmpty()) {
            return response()->json(['message' => 'Error: Goal or feedback not found'], 404);
        }

        return response()->json($goalFeedback);
    }

    // Update the specified resource in storage.
    public function update(Request $request/*, GoalFeedback $goalFeedback*/, $goalID/*, $feedbackID*/)
    {
        // $goalFeedback = GoalFeedback::findOrFail($feedbackID);

        // check feedback with goal id exists
        // $goalFeedback = GoalFeedback::where('goal_id','=', $goalID)->get();

        if (GoalFeedback::where('goal_id','=', $goalID)->get()->isEmpty()) {
            return response()->json(['message' => 'Error: Goal or feedback not found'], 404);
        }
        // $goalFeedback = GoalFeedback::find

        // validate request
        $validated = $request->validate([
            'feedback_content' => 'required|string',
        ]);

        GoalFeedback::where('goal_id','=', $goalID)->update(['feedback_content' => $validated['feedback_content']]);

        // // update feedback
        // $goalFeedback->update(['feedback_content' => $validated['feedback_content']]);

        return response()->json(GoalFeedback::where('goal_id','=', $goalID)->get());
    }

    // Remove the specified resource from storage.
    public function destroy(/*GoalFeedback $goalFeedback,*/ $goalID)
    {
        // check feedback with goal id exists
        $goalFeedback = GoalFeedback::where('goal_id','=', $goalID)->get();
        if ($goalFeedback->isEmpty()) {
            return response()->json(['message' => 'Error: Goal or feedback not found'], 404);
        }

        $goalFeedback->each->delete();
        return response()->json(['message' => 'Goal feedback deleted successfully']);
    }
}
