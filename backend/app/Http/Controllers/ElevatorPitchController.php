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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $profile)
    {
        //
        $validated = $request->validate([
            'pitch_text' => 'nullable|string',
        ]);

        $pitch = ElevatorPitch::updateOrCreate(
            ['profile_id' => $profile],
            ['pitch_text' => $validated['pitch_text'] ?? '']
        );

        return response()->json($pitch, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($profile)
    {
        //
        $pitch = ElevatorPitch::where('profile_id', $profile)->first();

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
        //
        $validated = $request->validate([
            'pitch_text' => 'nullable|string',
        ]);

        $pitch = ElevatorPitch::updateOrCreate(
            ['profile_id' => $profile],
            ['pitch_text' => $validated['pitch_text'] ?? '']
        );

        return response()->json($pitch);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ElevatorPitch $elevatorPitch)
    {
        //
    }
}
