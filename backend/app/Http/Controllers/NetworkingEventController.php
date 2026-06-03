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
        //return every networking event tgt with its related questions, comment, and contacts
        return NetworkingEvent::with(['questions','comments','contacts'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate the event from data and make sure selected contact IDs really exist 
        $validated = $request->validate([
            'profile_id' => ['required', 'exists:student_profiles,profile_id'],
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'location' => ['nullable', 'string', 'max:100'],
            'details' => ['nullable', 'string'],
            'contact_ids' => ['nullable', 'array'],
            'contact_ids.*' => ['exists:industry_contacts,contact_id'],
        ]);
        //Map the frontend field names to the database column names when creating the event
        $event = NetworkingEvent::create([
            'profile_id' => $validated['profile_id'],
            'event_name' => $validated['name'],
            'event_datetime' => $validated['date'],
            'location' => $validated['location'] ?? null,
            'details' => $validated['details'] ?? null,
        ]);
        //Save the selected related contacts in the event-contact pivot table 
        $event->contacts()->sync($validated['contact_ids'] ?? []);

        //Return the new event with its related data so the frontend get a complete response 
        return $event->load(['questions','comments','contacts']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //Return one event by id tgt with its related questions, comments, and contacts
        return NetworkingEvent::with(['questions','comments','contacts'])->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //validate the updated event form data before saving changes 
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'location' => ['nullable', 'string', 'max:100'],
            'details' => ['nullable', 'string'],
            'contact_ids' => ['nullable', 'array'],
            'contact_ids.*' => ['exists:industry_contacts,contact_id'],
        ]);      

        //Find the event that should be updated  
        $event = NetworkingEvent::findOrFail($id);

        //Update the event fields using the database column names
        $event->update([
            'event_name' => $validated['name'],
            'event_datetime' => $validated['date'],
            'location' => $validated['location'] ?? null,
            'details' => $validated['details'] ?? null,
        ]);

        //Update the selected related contacts in the pivot table 
        $event->contacts()->sync($validated['contact_ids'] ?? []);

        //return the updated event tgt with its related data
        return $event->load(['questions', 'comments', 'contacts']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //Delete the event by its id 
        NetworkingEvent::destroy($id);
        //Return a simple success message to frontend 
        return ['message' => 'deleted'];
    }
}
