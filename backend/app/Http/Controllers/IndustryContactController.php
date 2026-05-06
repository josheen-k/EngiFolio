<?php

namespace App\Http\Controllers;

use App\Models\IndustryContact;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IndustryContactController extends Controller
{
    public function index($user)
    {
        $profile = StudentProfile::where('user_id', $user)->first();

        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        return response()->json(
            IndustryContact::where('profile_id', $profile->profile_id)->get()
        );
    }

    public function store(Request $request, $user)
    {
        $profile = StudentProfile::where('user_id', $user)->first();

        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $validated = $request->validate([
            'contact_name' => 'required|string|max:100',
            'company' => 'nullable|string|max:100',
            'progress_notes' => 'nullable|string',
            'date_met' => 'nullable|date',
        ]);

        $validated['profile_id'] = $profile->profile_id;

        $contact = IndustryContact::create($validated);

        return response()->json($contact, 201);
    }

    public function show($user, IndustryContact $industryContact)
    {
        return response()->json($industryContact);
    }

    public function update(Request $request, $user, IndustryContact $industryContact)
    {
        $validated = $request->validate([
            'contact_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'progress_notes' => 'nullable|string',
            'date_met' => 'nullable|date',
        ]);

        $industryContact->update($validated);

        return response()->json($industryContact);
    }

    public function destroy($user, IndustryContact $industryContact)
    {
        Log::info('Deleting contact with ID: ' . $industryContact->contact_id);

        $industryContact->delete();

        return response()->json(['message' => 'Deleted']);
    }
}