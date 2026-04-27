<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountStatusController extends Controller
{
    /**
     * Display a listing of all the statuses
     */
    public function index()
    {
        $statuses = AccountStatus::all();
        return response()->json($statuses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Make sure request fits the database constraints and is unique
        $validated = $request->validate([
            'account_status' => 'required|string|max:20|unique:account_statuses,account_status',
        ]);

        $status = AchievementCert::create($validated);
        
        return response()->json($status, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {

    }
}
