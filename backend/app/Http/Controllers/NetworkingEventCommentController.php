<?php

namespace App\Http\Controllers;

use App\Models\NetworkingEvent;
use App\Models\NetworkingEventComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NetworkingEventCommentController extends Controller
{
    //display all comments for one networking event
    public function index($eventId)
    {   
        //get every comment that belongs to the selected event
        return NetworkingEventComment::where('event_id', $eventId) ->get();
    }
    
    //store a new comment for a networking event
    public function store(Request $request, $eventId)
    {   
        //validate the incoming form data
        $validated = $request->validate([
            'comment_type' => ['required', 'in:link,image,video'],
            'link_url' => ['nullable', 'url'],
            'file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,mp4,mov,avi', 'max:10240'],
        ]);

        //if the user chose "link", a real URL must be provided
        if ($validated['comment_type'] === 'link' && empty($validated['link_url'])) {
            return response()->json([
                'message' => 'A link URL is required when comment type is link.',
            ],422);
        }

        //if the user chose image or video, a file must be uploaded 
        if(in_array($validated['comment_type'],['image','video'],true) && !$request->hasFile('file')) {
            return response()->json([
                'message' => 'A file is required when comment type is image or video.',
            ],422);
        }

        //build the base comment data before deciding whether it is a link or file 
        $data = [
            'event_id' => $eventId,
            'comment_text' => null,
            'comment_type' => $validated['comment_type'],
            'link_url' => null,
            'file_path' => null,
            'file_name' => null,
        ];

        //save the URL if the comment type is link
        if($validated['comment_type'] === 'link') {
            $data['link_url'] = $validated['link_url'];
        }

        //save the uploaded file into Laravel public storage if it is image/video
        if(in_array($validated['comment_type'], ['image','video'], true)){
            $file = $request->file('file');
            $path = $file->store('networking-comments', 'public');

            $data['file_path'] = $path;
            $data['file_name'] = $file->getClientOriginalName();
        }

        //create and return the saved comment 
        return NetworkingEventComment::create($data);
    }

    //update an existing comment 
    public function update(Request $request, $id)
    {   
        //find the comment to update, or fail with 404 if it does not exist 
        $comment = NetworkingEventComment::findOrFail($id);

        //validate the updated input data
        $validated = $request->validate([
            'comment_type' => ['required', 'in:link,image,video'],
            'link_url' => ['nullable', 'url'],
            'file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,mp4,mov,avi', 'max:10240'],
        ]);

        //if the updated type is link, a URL is still required 
        if($validated['comment_type'] === 'link' && empty($validated['link_url'])) {
            return response()->json([
                'message' => 'A link URL is required when comment type is link.',
            ],422);
        }
        
        //If the updated type is image/video and no usable old file exists, then a new uploaded file is required 
        if(in_array($validated['comment_type'], ['image','video'], true) && !$request->hasFile('file') && (empty($comment->file_path) 
            || !in_array($comment->comment_type, ['image','video'], true) 
            || $comment->comment_type !== $validated['comment_type'])) 
        {
                return response()->json ([
                    'message' => 'A new file is required when changing to image or video.',
                ],422);
        }

        //start building the updated data
        $data = [
            'comment_text' => null,
            'comment_type' => $validated['comment_type'],
            'link_url' => null,
        ];

        //if switching to link: 1. delete old uploaded file if it exists 2. save the new link 3. clear file columns
        if($validated['comment_type'] === 'link'){
            if($comment->file_path) {
                Storage::disk('public')->delete($comment->file_path);
            }
            $data['link_url'] = $validated['link_url'];
            $data['file_path'] = null;
            $data['file_name'] = null;
        }
        
        //if the updated type is image/video, clear link_url, replace the file only if a new file was uploaded
        if(in_array($validated['comment_type'], ['image','video'], true)) {
            $data['link_url'] =null;

            if($request->hasFile('file')){
                //Remove the old file before saving the new one 
                if($comment->file_path) {
                    Storage::disk('public')->delete($comment->file_path);
                }

                $file = $request->file('file');
                $path = $file->store('networking-comments', 'public');

                $data['file_path'] = $path;
                $data['file_name'] = $file->getClientOriginalName();
            }
        }

        //save the updated comment data
        $comment->update($data);
        //return the updated comment 
        return $comment;

    }

    //Delete a comment 
    public function destroy($id)
    {   
        //find the comment first 
        $comment = NetworkingEventComment::findOrFail($id);
        //if this comment has an uploaded file. delete the file from storage too 
        if($comment->file_path) {
            Storage::disk('public')->delete($comment->file_path);
        }

        //delete the database row 
        $comment->delete();
        //return a simple success message 
        return['message' => 'deleted'];
    }
}
