<?php

namespace App\Http\Controllers;

use App\Models\ElevatorPitch;
use Illuminate\Http\Request;

class ElevatorPitchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //this function is currently empty and not being used 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $profile)
    {
        //validate the incoming elevator pitch text 
        $validated = $request->validate([
            'pitch_text' => 'nullable|string',
        ]);

        //create a new elevator pitch for this profile if it does not exist yet.
        //if it already exists, update the existing one instead.
        $pitch = ElevatorPitch::updateOrCreate(
            ['profile_id' => $profile],
            ['pitch_text' => $validated['pitch_text'] ?? '']
        );

        //return the saved pitch as JSON with status 201
        return response()->json($pitch, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($profile)
    {
        //find the elevator pitch for this profile
        $pitch = ElevatorPitch::where('profile_id', $profile)->first();

        //return the profile id and pitch text 
        //if no pitch exists yet, return an empty string instead 
        return response()->json([
            'profile_id' => $profile,
            'pitch_text' => $pitch?->pitch_text?? '',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $profile)
    {
        //validate the incoming elevator pitch text 
        $validated = $request->validate([
            'pitch_text' => 'nullable|string',
        ]);

        //update the pitch if it exists, or create it if it does not 
        $pitch = ElevatorPitch::updateOrCreate(
            ['profile_id' => $profile],
            ['pitch_text' => $validated['pitch_text'] ?? '']
        );

        //return the updated pitch as JSON
        return response()->json($pitch);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ElevatorPitch $elevatorPitch)
    {
        //this function is currently empty and not being used 
    }
}
