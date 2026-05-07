<?php

namespace App\Http\Controllers;

use App\Models\NetworkingEventQuestion;
use Illuminate\Http\Request;

class NetworkingEventQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($eventId)
    {
        //
        return NetworkingEventQuestion::where('event_id', $eventId)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $eventId)
    {
        //
        if (!$request->question || !trim($request->question)) {
            return response()->json(['error' => 'Question cannot be empty'], 400);
        }
        //find biggest order (if dont want can delete)
        $maxOrder = NetworkingEventQuestion::where('event_id', $eventId) -> max('question_order');
        //from 1
        $newOrder = $maxOrder ? $maxOrder +1:1;
        return NetworkingEventQuestion::Create([
            'event_id' => $eventId,
            'question_text' => $request->question,
            'question_order' => $newOrder
        ]);

        return response()->json($question,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(NetworkingEventQuestion $networkingEventQuestion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $question = NetworkingEventQuestion::findOrFail($id);

        $question->update([
            'question_text' => $request->question
        ]);
        return $question;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        NetworkingEventQuestion::destroy($id);
        return ['message' => 'deleted'];
    }
}
