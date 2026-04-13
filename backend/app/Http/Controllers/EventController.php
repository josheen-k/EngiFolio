<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;


class EventController extends Controller
{
    //
    public function index() {
        return Event::all();
    }

    public function store(Request $request){
        return Event::create([
            'name' => $request->name,
            'date' => $request->date,
            'location' => $request->location,
            'details' => $request->details
        ]);
    }

    public function destroy($id) {
        Event::destroy($id);
        return ['message' => 'deleted'];
    }

    public function update(Request $request, $id) {
        $event = Event::findOrFail($id);

        $event->update([
            'name' => $request->name,
            'date' => $request->date,
            'location' => $request->location,
            'details' => $request->details,
        ]);
    }

    public function questions($id) {
        $event = Event::with('questions') ->findOrFail($id);
    }

    public function storeQuestion(Request $request, $id){
        $event = Event::findOrFail($id);

        $question = $event->questions()->create([
            'question' => $request->question
        ]);
        return $question;
    }

}
