<?php

namespace App\Http\Controllers;

use App\Models\IndustryContact;
use App\Models\IndustryContactMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IndustryContactController extends Controller
{
    public function index($profile)
    {
        $contacts = IndustryContact::with('contactMethods')
            ->where('profile_id', $profile)
            ->get()
            ->map(function ($contact) {
                return [
                    'contact_id' => $contact->contact_id,
                    'profile_id' => $contact->profile_id,
                    'contact_name' => $contact->contact_name,
                    'company' => $contact->company,
                    'progress_notes' => $contact->progress_notes,
                    'date_met' => $contact->date_met,
                    'contact_methods' => $contact->contactMethods->map(function ($method) {
                        return [
                            'type' => $method->method_type,
                            'value' => $method->method_value,
                        ];
                    })->values(),
                ];
            });

        return response()->json($contacts);
    }

    public function store(Request $request, $profile)
    {
        $validated = $request->validate([
            'contact_name' => 'required|string|max:100',
            'company' => 'nullable|string|max:100',
            'progress_notes' => 'nullable|string',
            'date_met' => 'nullable|date',

            'contact_methods' => 'nullable|array',
            'contact_methods.*.type' => 'required|string|max:50', // * for automatic indexing for every item in the array
            'contact_methods.*.value' => 'required|string|max:255',
        ]);

        $contact = IndustryContact::create([
            'profile_id' => $profile,
            'contact_name' => $validated['contact_name'],
            'company' => $validated['company'] ?? null,
            'progress_notes' => $validated['progress_notes'] ?? null,
            'date_met' => $validated['date_met'] ?? null,
        ]);

        foreach ($validated['contact_methods'] ?? [] as $method) {
            IndustryContactMethod::create([
                'contact_id' => $contact->contact_id,
                'method_type' => $method['type'],
                'method_value' => $method['value'],
            ]);
        }

        return response()->json($contact->load('contactMethods'), 201);
    }

    public function show($profile, IndustryContact $industryContact)
    {
        $industryContact->load('contactMethods');

        return response()->json([
            'contact_id' => $industryContact->contact_id,
            'profile_id' => $industryContact->profile_id,
            'contact_name' => $industryContact->contact_name,
            'company' => $industryContact->company,
            'progress_notes' => $industryContact->progress_notes,
            'date_met' => $industryContact->date_met,
            'contact_methods' => $industryContact->contactMethods->map(function ($method) {
                return [
                    'type' => $method->method_type,
                    'value' => $method->method_value,
                ];
            })->values(),
        ]);
    }

    public function update(Request $request, $profile, IndustryContact $industryContact)
    {
        $validated = $request->validate([
            'contact_name' => 'required|string|max:100',
            'company' => 'nullable|string|max:100',
            'progress_notes' => 'nullable|string',
            'date_met' => 'nullable|date',

            'contact_methods' => 'nullable|array',
            'contact_methods.*.type' => 'required|string|max:50',
            'contact_methods.*.value' => 'required|string|max:255',
        ]);

        $industryContact->update([
            'contact_name' => $validated['contact_name'],
            'company' => $validated['company'] ?? null,
            'progress_notes' => $validated['progress_notes'] ?? null,
            'date_met' => $validated['date_met'] ?? null,
        ]);

        $industryContact->contactMethods()->delete();

        foreach ($validated['contact_methods'] ?? [] as $method) {
            IndustryContactMethod::create([
                'contact_id' => $industryContact->contact_id,
                'method_type' => $method['type'],
                'method_value' => $method['value'],
            ]);
        }

        return response()->json($industryContact->load('contactMethods'));
    }

    public function destroy($profile, IndustryContact $industryContact)
    {
        Log::info('Deleting contact with ID: ' . $industryContact->contact_id);

        $industryContact->delete();

        return response()->json(['message' => 'Deleted']);
    }
}