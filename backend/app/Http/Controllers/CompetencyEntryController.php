<?php

namespace App\Http\Controllers;

use App\Models\CompetencyEntry;
use Illuminate\Http\Request;

class CompetencyEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($profileId)
    {
        $entries = CompetencyEntry::with('indicator', 'entryLevel')->where('profile_id', $profileId)->get();

        if ($entries->isEmpty()) {
            return response()->json(['message' => 'No comptencies for this user found'], 404);
        }

        return response()->json($entries);
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
    public function show()
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompetencyEntry $competencyEntry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompetencyEntry $competencyEntry)
    {
        //
    }
}
