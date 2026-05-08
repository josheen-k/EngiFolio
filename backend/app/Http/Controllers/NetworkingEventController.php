<?php

namespace App\Http\Controllers;

use App\Models\NetworkingEvent;
use Illuminate\Http\Request;

class NetworkingEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return NetworkingEvent::with(['questions','comments','contacts'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'profile_id' => ['required', 'exists:student_profiles,profile_id'],
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'location' => ['nullable', 'string', 'max:100'],
            'details' => ['nullable', 'string'],
            'contact_ids' => ['nullable', 'array'],
            'contact_ids.*' => ['exists:industry_contacts,contact_id'],
        ]);
        $event = NetworkingEvent::create([
            'profile_id' => $validated['profile_id'],
            'event_name' => $validated['name'],
            'event_datetime' => $validated['date'],
            'location' => $validated['location'] ?? null,
            'details' => $validated['details'] ?? null,
        ]);

        $event->contacts()->sync($validated['contact_ids'] ?? []);
        return $event->load(['questions','comments','contacts']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        return NetworkingEvent::with(['questions','comments','contacts'])->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'location' => ['nullable', 'string', 'max:100'],
            'details' => ['nullable', 'string'],
            'contact_ids' => ['nullable', 'array'],
            'contact_ids.*' => ['exists:industry_contacts,contact_id'],
        ]);      
        $event = NetworkingEvent::findOrFail($id);
        $event->update([
            'event_name' => $validated['name'],
            'event_datetime' => $validated['date'],
            'location' => $validated['location'] ?? null,
            'details' => $validated['details'] ?? null,
        ]);
        $event->contacts()->sync($validated['contact_ids'] ?? []);
        return $event->load(['questions', 'comments', 'contacts']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        NetworkingEvent::destroy($id);
        return ['message' => 'deleted'];
    }
}
