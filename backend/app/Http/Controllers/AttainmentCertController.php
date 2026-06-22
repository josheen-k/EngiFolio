<?php

namespace App\Http\Controllers;

use App\Models\AttainmentCert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AttainmentCertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(AttainmentCert::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'profile_id'     => 'required|exists:student_profiles,profile_id', 
            'title'       => 'required|string|max:100',
            'body'        => 'nullable|string',
            'file_path'   => 'nullable|string|max:255',
            'issued_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'sort_order'  => 'required|integer'
        ]);

        $cert = AttainmentCert::create($validated);
        
        return response()->json($cert, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cert = AttainmentCert::findOrFail($id);

        $validated = $request->validate([
            'profile_id'     => 'required|exists:student_profiles,profile_id', 
            'title'       => 'required|string|max:100',
            'body'        => 'nullable|string',
            'file_path'   => 'nullable|string|max:255',
            'issued_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'sort_order'  => 'required|integer'
        ]);

        $cert->update($validated);

        return response()->json(['message' => 'Certificate updated successfully', 'cert' => $cert]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        $cert = AttainmentCert::findOrFail($id);

        // Deletes existing cert, find in storage folder with a file that starts with the url 
        if ($cert && $cert->file_path) {
            // Breaks the url into its parts
            $parsed = parse_url($cert->file_path);
            // Remove /storage/ as backend already knows this
            $storagePath = str_replace('/storage/', '', $parsed['path']);
            Storage::disk('public')->delete($storagePath);
        }

        $cert->delete();

        return response()->json(['message' => 'Attainment certificate successfully deleted']);
    }
}
