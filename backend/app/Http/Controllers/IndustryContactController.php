<?php

namespace App\Http\Controllers;

use App\Models\IndustryContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IndustryContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($user)
    {
        return response()->json(
            IndustryContact::where('user_id', $user)->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $user)
{
    $validated = $request->validate([
        'contact_name' => 'required|string',
        'company' => 'nullable|string|max:255',
        'progress_notes' => 'nullable|string',
        'date_met' => 'nullable|date',
    ]);

    $validated['user_id'] = $user; //manually sending the user id for now, once login/authenication systemis created, will integrate it

    $contact = IndustryContact::create($validated);

    return response()->json($contact, 201);
}

    /**
     * Display the specified resource.
     */
    public function show($user, IndustryContact $industryContact)
    {
        return response()->json($industryContact);
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user, IndustryContact $industryContact)
    {
        Log::info('Deleting contact with ID: ' . $industryContact->contact_id);
        $industryContact->delete();
        return response()->json(['message' => 'Deleted']);
    }
}