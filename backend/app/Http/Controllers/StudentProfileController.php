<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\User;
// For storing the profile picture
use Illuminate\Support\Facades\Storage;

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
        ]);

        $profile = StudentProfile::create($validated);
        
        return response()->json($profile, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $studentProfile = StudentProfile::with([
            'links', 
            'user', 
            'achievementCerts' => function ($query) {$query->orderBy('sort_order', 'asc');}, 
            'attainmentCerts' => function ($query) {$query->orderBy('sort_order', 'asc');}
        ])->findOrFail($id);

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

    // Called by the dashboard to get relevant profile data for the dashboard display
    public function getDashboardInfo($id)
    {
        // Fails if no profile is found
        $studentProfile = StudentProfile::with([
            'user',
            'actions',
            'competencyEntries.entryLevel',
        ])->findOrFail($id);

        return response()->json($studentProfile);
    }

    public function exportPdf(Request $request, $id)
    {
        // Get tables of all data that can be exported
        $profile = StudentProfile::with([
            'user', 
            'competencyEntries', 
            'competencyEntries.indicator', 
            'competencyEntries.entryLevel',  
            'competencyEntries.entryStatus',
            'industryContacts',
            'industryContacts.contactMethods',
            'careerPlans',
            'smartGoals',
            'smartGoals.actionSteps',
            'smartGoals.status',
            'achievementCerts',
            'attainmentCerts', 
        ])->findOrFail($id);

        // Fields selected. Passed by front end
        $selections = $request->input('selections', []);

        // Use pdf template to generate pdf for downloading
        $pdf = Pdf::loadView('portfolio', [
            'profile' => $profile, 
            'selections' => $selections
        ]);
        
        return $pdf->download("portfolio.pdf");
    }

    public function uploadImage(Request $request, $id) {
        // Only accept the 3 file formats
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        $profile = StudentProfile::findOrFail($id);

        // Deletes existing profile, find in storage folder with a file that starts with the url 
        if ($profile->profile_image_url) {
            // Breaks the url into its parts
            $parsed = parse_url($profile->profile_image_url);
            // Remove /storage/ as backend already knows this
            $storagePath = str_replace('/storage/', '', $parsed['path']);
            Storage::disk('public')->delete($storagePath);
        }

        // Gets the image from the request and saves it on the public disk in the profile-images file
        $path = $request->file('image')->store('profile-images', 'public');

        // Concatenate the full url using the pathway stored in .env
        $fullUrl = config('app.url') . '/storage/' . $path;

        $profile->profile_image_url = $fullUrl;
        $profile->save();
        return response()->json(['image_url' => '/storage/' . $path]);
    }

    // Returns only the profile image url from the student profile table
    public function getProfileImage($id) {
        $profile = StudentProfile::findOrFail($id);
        return response()->json(['profile_image_url' => $profile->profile_image_url]);
    }

    // Return only the achievement and attainment certs relating to a profile
    public function getCertifications($id) {
    $profile = StudentProfile::with(['achievementCerts', 'attainmentCerts'])->findOrFail($id);
    return response()->json([
        'achievement_certs' => $profile->achievementCerts,
        'attainment_certs' => $profile->attainmentCerts,
    ]);
}
}
