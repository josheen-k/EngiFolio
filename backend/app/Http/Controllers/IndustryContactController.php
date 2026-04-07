<?php

namespace App\Http\Controllers;

use App\Models\IndustryContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IndustryContactController extends Controller
{
    public function index()
    {
        return response()->json(IndustryContact::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_name' => 'required|string',
            'email_id' => 'nullable|string',
            'company_name' => 'nullable|string|max:255',
            'date_met' => 'nullable|date',
        ]);

     
        $contact = IndustryContact::create($validated);

        return response()->json($contact, 201);
    }

    public function show(IndustryContact $contact)
    {
        return response()->json(IndustryContact::all());
    }

    public function update(Request $request, IndustryContact $contact)
    {
        $validated = $request->validate([
            'contact_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email_id' => 'nullable|string',
            'date_met' => 'nullable|date',
        ]);

        $contact->update($validated);

        return response()->json($contact);
    }

    public function destroy(IndustryContact $contact)
    {
        Log::info('Deleting contact with ID: ' . $contact->id);
        $contact->delete();
        return response()->json(['message' => 'Deleted']);
    }
}