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
        return NetworkingEvent::with(['questions','comments'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        return NetworkingEvent::create([
            'user_id' => null,
            'event_name' => $request->name,
            'event_datetime' => $request->date,
            'location' => $request->location,
            'details' => $request->details,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        return NetworkingEvent::with('questions')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $event = NetworkingEvent::findOrFail($id);
        $event->update([
            'event_name' => $request->name,
            'event_datetime' => $request->date,
            'location' => $request->location,
            'details' => $request->details,
        ]);
        return $event;
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
