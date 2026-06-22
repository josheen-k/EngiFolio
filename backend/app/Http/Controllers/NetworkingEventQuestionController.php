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
        //return all questions that belong to the selected event
        return NetworkingEventQuestion::where('event_id', $eventId)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $eventId)
    {
        //stop the save if the question is missing or only contains spaces
        if (!$request->question || !trim($request->question)) {
            return response()->json(['error' => 'Question cannot be empty'], 400);
        }
        //find biggest order (if don't want can delete)
        $maxOrder = NetworkingEventQuestion::where('event_id', $eventId) -> max('question_order');
        //if there are already questions, put the new one after the last question
        $newOrder = $maxOrder ? $maxOrder +1:1;

        //create and return the new question
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
        //this function is currently empty and not being used 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //find the question to update, or return 404 if it does not exist 
        $question = NetworkingEventQuestion::findOrFail($id);

        //update the question text with the new value from the request 
        $question->update([
            'question_text' => $request->question
        ]);

        //return the updated question
        return $question;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //delete the question by its id 
        NetworkingEventQuestion::destroy($id);
        //return a simple success message 
        return ['message' => 'deleted'];
    }
}
