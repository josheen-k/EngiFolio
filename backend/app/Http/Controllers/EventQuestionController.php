<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventQuestion;

class EventQuestionController extends Controller
{
    //
    public function index($id){
        return EventQuestion::where('event_id',$id)->get();
    }

    public function store(Request $request, $id){
        return EventQuestion::create([
            'event_id' => $id,
            'question' =>$request->question
        ]);
    }

    public function update(Request $request, $id){
        $question = EventQuestion::findOrFail($id);

        $question->update([
            'question' => $request->question
        ]);
        return $question;
    }

    public function destroy($id){
        EventQuestion::destroy($id);
        return['message' => 'deleted'];
    }
}
