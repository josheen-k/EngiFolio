<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,role_id',
            'username' => 'required|string|max:9|unique:users',
            'email' => 'required|email|max:254|unique:users',
            'first_name'       => 'nullable|string|max:50',
            'last_name'        => 'required|string|max:50',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'role_id' => $validated['role_id'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'first_name'       => $validated['first_name'],
            'last_name'        => $validated['last_name'],
            // Persist secure hash instead of storing raw password.
            'password_hash' => Hash::make($validated['password']),
            // Default new users to active status.
            'account_status_id' => 1,
        ]);

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,role_id',
            'username' => 'required|string|max:100|unique:users,username,' . $user->user_id . ',user_id',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'first_name'       => 'nullable|string|max:50',
            'last_name'        => 'required|string|max:50',
            'password' => 'required|min:6',
            'account_status_id' => 'required|exists:account_statuses,account_status_id'
        ]);

        if (isset($validated['password'])) {
            $validated['password_hash'] = Hash::make($validated['password']);
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
