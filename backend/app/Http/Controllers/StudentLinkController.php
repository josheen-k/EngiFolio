<?php

namespace App\Http\Controllers;

use App\Models\StudentLink;
use Illuminate\Http\Request;

class StudentLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(StudentLink::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'profile_id' => 'required|exists:student_profiles,profile_id',
            'link_type'          => 'nullable|string|max:255',
            'link_label'         => 'required|string|max:255',
            'link_url'           => 'required|url',
            'display_order'      => 'nullable|integer',
        ]);

        $link = StudentLink::create($validated);

        return response()->json($link, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $link = StudentLink::findOrFail($id);

        $validated = $request->validate([
            'profile_id'  => 'required|exists:student_profiles,profile_id',
            'link_type'   => 'nullable|string|max:255',
            'link_label'  => 'required|string|max:255',
            'link_url'    => 'required|url',
        ]);

        $link->update($validated);

        return response()->json(['message' => 'Link updated successfully', 'link' => $link]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        StudentLink::findOrFail($id)->delete();

        return response()->json(['message' => 'Link successfully deleted']);
    }
}
