<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use Illuminate\Http\Request;

class StudentProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profile = StudentProfile::with('links')->where('user_id', Auth::id())->first();

        if(!$profile) {
            return response()->json(['error' => 'Profile was not found'], 404);
        }

        return response()->json($profile);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $studentProfile = StudentProfile::with('links')->find($id);

    if (!$studentProfile) {
        return response()->json(['message' => 'Profile not found'], 404);
    }

    return response()->json($studentProfile);
}



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $profile = \App\Models\StudentProfile::findOrFail($id);

        $validated = $request->validate([
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'preferred_name'   => 'nullable|string|max:255',
            'degree_title'     => 'nullable|string|max:255',
            'personal_intro'   => 'nullable|string',
            'upcoming_actions' => 'nullable|string',
        ]);

        $profile->update($validated);

        return response()->json([
            'message' => 'Updated successfully',
            'profile' => $profile
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentProfile $studentProfile)
    {
        //
    }
}
