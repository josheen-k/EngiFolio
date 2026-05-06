<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function usersOverview(Request $request)
    {
        // Allow lightweight server-side search for the admin page table.
        $validated = $request->validate([
            'q' => 'nullable|string|max:100',
        ]);

        // Build one aggregated dataset so frontend can render table + stats in a single call.
        $query = User::query()
            ->leftJoin('roles as r', 'r.role_id', '=', 'users.role_id')
            ->leftJoin('student_profiles as sp', 'sp.user_id', '=', 'users.user_id')
            ->leftJoin('career_development_plans as cdp', 'cdp.profile_id', '=', 'sp.profile_id')
            ->leftJoin('smart_goals as sg', 'sg.plan_id', '=', 'cdp.plan_id')
            ->select([
                'users.user_id',
                'users.username',
                'users.email',
                'users.first_name',
                'users.last_name',
                'sp.profile_id',
                'users.updated_at as user_updated_at',
                'r.role_name',
                DB::raw('COUNT(DISTINCT sg.goal_id) as goals_count'),
                DB::raw('SUM(CASE WHEN sg.goal_status_id = 3 THEN 1 ELSE 0 END) as completed_goals_count'),
                DB::raw('MAX(sg.updated_at) as goals_updated_at'),
            ])
            ->groupBy([
                'users.user_id',
                'users.username',
                'users.email',
                'users.first_name',
                'users.last_name',
                'sp.profile_id',
                'users.updated_at',
                'r.role_name',
            ]);

        if (!empty($validated['q'])) {
            $search = trim($validated['q']);
            // Search by common admin fields: name, email, or username.
            $query->where(function ($q) use ($search) {
                $q->where('users.email', 'like', "%{$search}%")
                    ->orWhere('users.username', 'like', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT(COALESCE(users.first_name, ''), ' ', COALESCE(users.last_name, ''))"), 'like', "%{$search}%");
            });
        }

        $rows = $query->orderBy('users.user_id')->get();

        $users = $rows->map(function ($row) {
            $goalsCount = (int) $row->goals_count;
            $completedGoalsCount = (int) $row->completed_goals_count;

            $name = trim(implode(' ', array_filter([$row->first_name, $row->last_name])));
            if ($name === '') {
                $name = $row->username;
            }

            // Prefer goal activity timestamp; fallback to user row update time.
            $lastUpdated = $row->goals_updated_at ?? $row->user_updated_at;
            $prefix = strtolower((string) $row->role_name) === 'admin' ? 'ADM' : 'STU';

            return [
                'user_id' => (int) $row->user_id,
                'profile_id' => $row->profile_id ? (int) $row->profile_id : null,
                'id' => sprintf('%s-%04d', $prefix, (int) $row->user_id),
                'username' => $row->username,
                'name' => $name,
                'email' => $row->email,
                'role' => $row->role_name ?? 'Unknown',
                'goals' => $goalsCount,
                'completedGoals' => $completedGoalsCount,
                // Query builder aggregate values come back as strings, so parse before formatting.
                'updatedAt' => $lastUpdated ? Carbon::parse($lastUpdated)->format('Y-m-d') : null,
            ];
        })->values();

        return response()->json([
            'stats' => [
                'totalUsers' => $users->count(),
                'totalGoals' => (int) $users->sum('goals'),
                'totalCompletedGoals' => (int) $users->sum('completedGoals'),
            ],
            'users' => $users,
        ]);
    }

    public function createUser(Request $request)
    {
        // Keep validation aligned with the admin create-user form constraints.
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,role_id',
            'username' => 'required|string|max:9|unique:users,username',
            'email' => 'required|email|max:254|unique:users,email',
            'first_name' => 'nullable|string|max:50',
            'last_name' => 'required|string|max:50',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'role_id' => (int) $validated['role_id'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'first_name' => $validated['first_name'] ?? null,
            'last_name' => $validated['last_name'],
            // Store a one-way hash only, never plaintext passwords.
            'password_hash' => Hash::make($validated['password']),
            // New accounts start as active.
            'account_status_id' => 1,
        ]);

        return response()->json([
            'message' => 'User created successfully.',
            'user' => $user,
        ], 201);
    }

    public function deleteUser(User $user)
    {
        // Route model binding resolves {user} by user_id (see User model getRouteKeyName()).
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
    }
}
