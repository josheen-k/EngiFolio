<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller 
{
    public function index() 
    {
        return response()->json(Post::all()); 
    }

    public function show(Post $post) {
        return response()->json($post);
    }

    public function store(Request $request) 
    {
        $validated = $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
        
        Post::create($validated);
        
        
        return response()->json([
            'message' => 'Post created successfully',
            ], 200);
    }


    public function update(Request $request, Post $post) 
    {
        $validated = $request->validate([
            'title' => 'required',
            'body'  => 'required',
        ]);

        $post->update($validated);

        return response()->json([
            'message' => 'Post updated successfully',
            'post'    => $post
            ], 200);
    }

    public function destroy(Post $post) {
        $post->delete();

        return response()->json([
        'message' => 'Post deleted successfully'
        ], 200);
    }
}
