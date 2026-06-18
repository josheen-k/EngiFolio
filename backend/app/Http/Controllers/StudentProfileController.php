<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CompetencyIndicator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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

        // Deletes existing profile picture, find in storage folder with a file that starts with the url 
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

        // Save the path to the profile
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

    // Count up the amount of max competency indicators the user has for each level
    public function competencyLevelCounts($profileId): array {

        // Get all possible levels that an entry can have
        $levels = DB::table('competency_entry_levels')->orderBy('competency_level_weighting')->pluck('competency_level');

        // Associative array to store levels and their counts
        $levelCounts = $levels->mapWithKeys(fn($l) => [$l => 0])->toArray();
        $attainedCount = 0;

        // Fetch the highest entry for each competency indicator
        $indicators = CompetencyIndicator::whereNull('discontinued_date')
            ->with(['highestEntry' => fn($q) => $q->where('profile_id', $profileId)])
            ->get();

        // Loop through each indicator and add a count to the level of the highest entry
        foreach ($indicators as $indicator) {
            $level = $indicator->highestEntry?->competency_level;
            if ($level) {
                $levelCounts[$level]++;
                $attainedCount++;
            }
        }

        // Return values
        return [
            'notStarted' => max(0, $indicators->count() - $attainedCount),
            'levels' => $levelCounts,
        ];
    }

    // Add a pdf file to a certificate
    public function uploadCertFile(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:2048',
            'type' => 'required|in:achievement,attainment',
            'cert_id' => 'nullable|integer',
        ]);

        StudentProfile::findOrFail($id);

        // Get the cert_id from the request and see where it is an achievement or attainment cert
        $certId = $request->input('cert_id');

        // If it is an existing cert then delete any existing file if a new file is uploaded
        if ($certId) {
            // Check the type sent from the front end to know what cert it is and find that cert
            if ($request->input('type') === 'achievement') {
                $cert = \App\Models\AchievementCert::find($certId);
                $folder = 'achievement-certs';
            } else {
                $cert = \App\Models\AttainmentCert::find($certId);
                $folder = 'attainment-certs';
            }

            // Deletes existing cert, find in storage folder with a file that starts with the url 
            if ($cert && $cert->file_path) {
                // Breaks the url into its parts
                $parsed = parse_url($cert->file_path);
                // Remove /storage/ as backend already knows this
                $storagePath = str_replace('/storage/', '', $parsed['path']);
                Storage::disk('public')->delete($storagePath);
            }
        }

    // Store the file and assemble the full path for the file and return it to the frontend
    $path = $request->file('file')->store($folder, 'public');
    $fullUrl = config('app.url') . '/storage/' . $path;

    return response()->json(['file_path' => $fullUrl]);

    }
}
