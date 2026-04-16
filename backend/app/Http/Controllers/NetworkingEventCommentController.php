<?php

namespace App\Http\Controllers;

use App\Models\NetworkingEventComment;
use Illuminate\Http\Request;

class NetworkingEventCommentController extends Controller
{
    //
    public function index($eventId)
    {
        return NetworkingEventComment::where('event_id', $eventId) ->get();
    }
    
    public function store(Request $request, $eventId)
    {
        return NetworkingEventComment::create([
            'event_id' => $eventId,
            'comment_text' => $request->comment
        ]);
    }

    public function update(Request $request, $id)
    {
        $comment = NetworkingEventComment::findOrFail($id);
        $comment->update([
            'comment_text' => $request->comment
        ]);

        return $comment;
    }

    public function destroy($id)
    {
        NetworkingEventComment::destroy($id);
        return ['message' => 'deleted'];
    }
}
