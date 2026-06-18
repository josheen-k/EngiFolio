<?php

namespace App\Http\Controllers;

use App\Models\CompetencyEvidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CompetencyEvidenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = CompetencyEvidence::get(); 

        return response()->json($groups);    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'entry_id'       => 'required|exists:competency_entries,entry_id',
            'evidence_type'  => 'required|string|max:15',
            'evidence_value' => 'nullable|string|max:500',
        ]);

        
    if ($request->input('evidence_type') === 'document') {
        $validated['evidence_value'] = $this->uploadDoc($request);
    } else if ($request->input('evidence_type') === 'image') {
        $validated['evidence_value'] = $this->uploadImage($request);
    }
    // For url/video, evidence_value is already in $validated from the request

    $evidence = CompetencyEvidence::create($validated);

    return response()->json($evidence, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CompetencyEvidence $CompetencyEvidence)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompetencyEvidence $CompetencyEvidence)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($CompetencyEvidenceId)
    {
        $entry  = CompetencyEvidence::findOrFail($CompetencyEvidenceId);

        if ($entry->evidence_value && ($entry->evidence_type === 'document' || $entry->evidence_type === 'image')) {
            // Breaks the url into its parts
            $parsed = parse_url($entry->evidence_value);
            // Remove /storage/ as backend already knows this
            $storagePath = str_replace('/storage/', '', $parsed['path']);
            Storage::disk('public')->delete($storagePath);
        }

        $entry->delete();

        return response()->json(['message' => 'Competency evidence successfully deleted'], 200);
    }

    // Called when evidence store is called
    public function uploadImage(Request $request) {
        // Only accept the 3 file formats
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        // Gets the image from the request and saves it on the public disk in the profile-images file
        $path = $request->file('image')->store('evidence-images', 'public');

        // Concatenate the full url using the pathway stored in .env
        $fullUrl = config('app.url') . '/storage/' . $path;

        return $fullUrl;
    }

    public function uploadDoc(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,txt,ppt,pptx|max:10240'
        ]);

        // Store the file and assemble the full path for the file and return it to the frontend
        $path = $request->file('file')->store('evidence-documents', 'public');

        // Concatenate the full url using the pathway stored in .env
        $fullUrl = config('app.url') . '/storage/' . $path;

        return $fullUrl;
    }
}

