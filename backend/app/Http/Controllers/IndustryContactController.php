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
                $link = $contact->contactMethods->firstWhere('method_type', 'link');

                return [
                    'contact_id' => $contact->contact_id,
                    'profile_id' => $contact->profile_id,
                    'contact_name' => $contact->contact_name,
                    'company' => $contact->company,
                    'progress_notes' => $contact->progress_notes,
                    'date_met' => $contact->date_met,
                    'link_url' => $link?->method_value,
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
            'link_url' => 'nullable|url|max:255',
            'date_met' => 'nullable|date',
        ]);

        $contact = IndustryContact::create([
            'profile_id' => $profile,
            'contact_name' => $validated['contact_name'],
            'company' => $validated['company'] ?? null,
            'progress_notes' => $validated['progress_notes'] ?? null,
            'date_met' => $validated['date_met'] ?? null,
        ]);

        if (!empty($validated['link_url'])) {
            IndustryContactMethod::create([
                'contact_id' => $contact->contact_id,
                'method_type' => 'link',
                'method_value' => $validated['link_url'],
            ]);
        }

        return response()->json($contact, 201);
    }

    public function show($profile, IndustryContact $industryContact)
    {
        $industryContact->load('contactMethods');

        $link = $industryContact->contactMethods->firstWhere('method_type', 'link');

        return response()->json([
            'contact_id' => $industryContact->contact_id,
            'profile_id' => $industryContact->profile_id,
            'contact_name' => $industryContact->contact_name,
            'company' => $industryContact->company,
            'progress_notes' => $industryContact->progress_notes,
            'date_met' => $industryContact->date_met,
            'link_url' => $link?->method_value,
        ]);
    }

    public function update(Request $request, $profile, IndustryContact $industryContact)
    {
        $validated = $request->validate([
            'contact_name' => 'required|string|max:100',
            'company' => 'nullable|string|max:100',
            'progress_notes' => 'nullable|string',
            'link_url' => 'nullable|url|max:255',
            'date_met' => 'nullable|date',
        ]);

        $industryContact->update([
            'contact_name' => $validated['contact_name'],
            'company' => $validated['company'] ?? null,
            'progress_notes' => $validated['progress_notes'] ?? null,
            'date_met' => $validated['date_met'] ?? null,
        ]);

        $linkMethod = $industryContact
            ->contactMethods()
            ->where('method_type', 'link')
            ->first();

        if (!empty($validated['link_url'])) {
            if ($linkMethod) {
                $linkMethod->update([
                    'method_value' => $validated['link_url'],
                ]);
            } else {
                IndustryContactMethod::create([
                    'contact_id' => $industryContact->contact_id,
                    'method_type' => 'link',
                    'method_value' => $validated['link_url'],
                ]);
            }
        } elseif ($linkMethod) {
            $linkMethod->delete();
        }

        return response()->json([
            'contact_id' => $industryContact->contact_id,
            'profile_id' => $industryContact->profile_id,
            'contact_name' => $industryContact->contact_name,
            'company' => $industryContact->company,
            'progress_notes' => $industryContact->progress_notes,
            'date_met' => $industryContact->date_met,
            'link_url' => $validated['link_url'] ?? null,
        ]);
    }

    public function destroy($profile, IndustryContact $industryContact)
    {
        Log::info('Deleting contact with ID: ' . $industryContact->contact_id);

        $industryContact->delete();

        return response()->json(['message' => 'Deleted']);
    }
}