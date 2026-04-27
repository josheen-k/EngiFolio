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
        $profile = StudentProfile::with(['user', 'links'])->get();

        // No need for error checking as get never returns null
        return response()->json($profile);      
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'preferred_name'   => 'nullable|string|max:50',
            'degree_title'     => 'nullable|string|max:40',
            'specialisation'     => 'nullable|string|max:60',
            'personal_intro'   => 'nullable|string',
            'profile_image_url' => 'nullable|string|max:255',
        ]);

        $profile = StudentProfile::create($validated);
        
        return response()->json($profile, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Fails if no profile is found
        $studentProfile = StudentProfile::with('links', 'user')->findOrFail($id);

        return response()->json($studentProfile);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Fails if no profile is found
        $profile = \App\Models\StudentProfile::findOrFail($id);

        // Validate all data coming in
        $validated = $request->validate([
            'preferred_name'   => 'nullable|string|max:50',
            'degree_title'     => 'nullable|string|max:40',
            'specialisation'     => 'nullable|string|max:60',
            'personal_intro'   => 'nullable|string',
            'profile_image_url' => 'nullable|string|max:255',
            'user.first_name'   => 'required|string|max:50',
            'user.last_name'    => 'required|string|max:50',
        ]);

        // Update profile with validated data
        $profile->update($validated);
        $profile->user->update($request->input('user'));

        return response()->json(['message' => 'Profile updated successfully', 'profile' => $profile]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $profile  = StudentProfile::findOrFail($id);
        $profile->delete();


        return response()->json(['message' => 'Profile successfully deleted']);
    }
}
