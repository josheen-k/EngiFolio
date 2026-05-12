<?php

namespace App\Http\Controllers;

use App\Models\CompetencyFeedback;
use App\Models\CompetencyEntry;
use Illuminate\Http\Request;

class CompetencyFeedbackController extends Controller
{
    public function index($entryId)
    {
        $feedback = CompetencyFeedback::where('entry_id', $entryId)
            ->with('staff')
            ->get();

        return response()->json($feedback);
    }

    public function store(Request $request, $entryId)
    {
        $entry = CompetencyEntry::where('entry_id', $entryId)->first();

        if (!$entry) {
            return response()->json(['message' => 'Competency entry not found'], 404);
        }

        $validated = $request->validate([
            'staff_id' => 'required|integer',
            'feedback_content' => 'required|string',
        ]);

        $feedback = CompetencyFeedback::create([
            'entry_id' => $entryId,
            'staff_id' => $validated['staff_id'],
            'feedback_content' => $validated['feedback_content'],
        ]);

        return response()->json($feedback, 201);
    }
}