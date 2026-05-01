<?php

namespace App\Http\Controllers;

use App\Models\NetworkingEvent;
use App\Models\NetworkingEventComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NetworkingEventCommentController extends Controller
{
    //
    public function index($eventId)
    {
        return NetworkingEventComment::where('event_id', $eventId) ->get();
    }
    
    public function store(Request $request, $eventId)
    {   
        /* check */
        $validated = $request->validate([
            'comment_type' => ['required', 'in:link,image,video'],
            'link_url' => ['nullable', 'url'],
            'file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,mp4,mov,avi', 'max:10240'],
        ]);

        if ($validated['comment_type'] === 'link' && empty($validated['link_url'])) {
            return response()->json([
                'message' => 'A link URL is required when comment type is link.',
            ],422);
        }

        if(in_array($validated['comment_type'],['image','video'],true) && !$request->hasFile('file')) {
            return response()->json([
                'message' => 'A file is required when comment type is image or video.',
            ],422);
        }

        $data = [
            'event_id' => $eventId,
            'comment_text' => null,
            'comment_type' => $validated['comment_type'],
            'link_url' => null,
            'file_path' => null,
            'file_name' => null,
        ];

        if($validated['comment_type'] === 'link') {
            $data['link_url'] = $validated['link_url'];
        }

        if(in_array($validated['comment_type'], ['image','video'], true)){
            $file = $request->file('file');
            $path = $file->store('networking-comments', 'public');

            $data['file_path'] = $path;
            $data['file_name'] = $file->getClientOriginalName();
        }
        return NetworkingEventComment::create($data);
    }

    public function update(Request $request, $id)
    {
        $comment = NetworkingEventComment::findOrFail($id);

        $validated = $request->validate([
            'comment_type' => ['required', 'in:link,image,video'],
            'link_url' => ['nullable', 'url'],
            'file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,mp4,mov,avi', 'max:10240'],
        ]);

        if($validated['comment_type'] === 'link' && empty($validated['link_url'])) {
            return response()->json([
                'message' => 'A link URL is required when comment type is link.',
            ],422);
        }
        if(in_array($validated['comment_type'], ['image','video'], true) && !$request->hasFile('file') && (empty($comment->file_path) 
            || !in_array($comment->comment_type, ['image','video'], true) 
            || $comment->comment_type !== $validated['comment_type'])) 
        {
                return response()->json ([
                    'message' => 'A new file is required when changing to image or video.',
                ],422);
        }

        $data = [
            'comment_text' => null,
            'comment_type' => $validated['comment_type'],
            'link_url' => null,
        ];

        if($validated['comment_type'] === 'link'){
            if($comment->file_path) {
                Storage::disk('public')->delete($comment->file_path);
            }
            $data['link_url'] = $validated['link_url'];
            $data['file_path'] = null;
            $data['file_name'] = null;
        }
        
        if(in_array($validated['comment_type'], ['image','video'], true)) {
            $data['link_url'] =null;

            if($request->hasFile('file')){
                if($comment->file_path) {
                    Storage::disk('public')->delete($comment->file_path);
                }

                $file = $request->file('file');
                $path = $file->store('networking-comments', 'public');

                $data['file_path'] = $path;
                $data['file_name'] = $file->getClientOriginalName();
            }
        }

        $comment->update($data);
        return $comment;

    }

    public function destroy($id)
    {
        $comment = NetworkingEventComment::findOrFail($id);
        if($comment->file_path) {
            Storage::disk('public')->delete($comment->file_path);
        }

        $comment->delete();

        return['message' => 'deleted'];
    }
}
