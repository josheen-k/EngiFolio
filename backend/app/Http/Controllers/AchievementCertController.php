<?php

namespace App\Http\Controllers;

use App\Models\AchievementCert;
use Illuminate\Http\Request;

class AchievementCertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(AchievementCert::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'body'        => 'nullable|text',
            'file_path'   => 'nullable|string|max:500',
            'issued_date'     => 'nullable|string|max:255',
            'expiry_date'     => 'nullable|string|max:255',
        ]);

        $cert = AchievementCert::create($validated);
        
        return response()->json($cert, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AchievementCert $achievementCert)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AchievementCert $achievementCert)
    {
        //
    }
}
