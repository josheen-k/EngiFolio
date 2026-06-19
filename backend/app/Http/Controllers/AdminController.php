<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use App\Models\User;
use App\Models\CompetencyEntryLevel;
use App\Models\CompetencyIndicator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\StudentProfileController;

class AdminController extends Controller
{
    /**
     * JSON for admin User Management table and summary stats
     */
    public function usersOverview(Request $request)
    {
        $data = $this->buildUsersOverview($request);

        return response()->json($data);
    }

    /**
     * PDF download; uses the same rows/stats as usersOverview
     */
    public function exportUsersOverviewPdf(Request $request)
    {
        $data = $this->buildUsersOverview($request);

        // Get the possible level names that can be shown in the table
        $levelNames = CompetencyEntryLevel::orderBy('competency_level_weighting')->pluck('competency_level');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin-users-overview', [
            'stats' => $data['stats'],
            'roleSections' => $this->groupUsersByRoleForPdf($data['users']),
            'levelNames' => $levelNames,
        ]);

        return $pdf->download('user_management_export.pdf');
    }

    /**
     * Build user rows and totals for the admin table, CSV (frontend), and PDF.
     * Returns ['stats' => [...], 'users' => Collection of row arrays].
     */
    private function buildUsersOverview(Request $request): array  {
        $validated = $request->validate([
            'q' => 'nullable|string|max:100',
        ]);

        $query = User::query()
            ->leftJoin('roles as r', 'r.role_id', '=', 'users.role_id')
            ->leftJoin('student_profiles as sp', 'sp.user_id', '=', 'users.user_id')
            ->leftJoin('smart_goals as sg', 'sg.profile_id', '=', 'sp.profile_id')
            ->select([
                'users.user_id',
                'users.username',
                'users.email',
                'users.first_name',
                'users.last_name',
                'sp.profile_id',
                'sp.year_started',
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
                'sp.year_started',
                'users.updated_at',
                'r.role_name',
            ]);

        if (! empty($validated['q'])) {
            $search = trim($validated['q']);
            $query->where(function ($q) use ($search) {
                $q->where('users.email', 'like', "%{$search}%")
                    ->orWhere('users.username', 'like', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT(COALESCE(users.first_name, ''), ' ', COALESCE(users.last_name, ''))"), 'like', "%{$search}%");
            });
        }

        $rows = $query->orderBy('users.user_id')->get();

        // Count the total EA competency indicators
        $totalIndicators = CompetencyIndicator::whereNull('discontinued_date')->count();


        $profileController = new StudentProfileController();

        $users = $rows->map(function ($row) use ($totalIndicators, $profileController) {
            $name = trim(implode(' ', array_filter([$row->first_name, $row->last_name])));
            if ($name === '') {
                $name = $row->username;
            }

            // Get the numbers for the indicators if the user has a student profile
            $levels = $row->profile_id ? $profileController->competencyLevelCounts($row->profile_id)
                : ['notStarted' => $totalIndicators, 'levels' => []];

            $goalsCount = (int) $row->goals_count;
            $completedGoalsCount = (int) $row->completed_goals_count;
            $lastUpdated = $row->goals_updated_at ?? $row->user_updated_at;

            return [
                'user_id'      => (int) $row->user_id,
                'profile_id'   => $row->profile_id ? (int) $row->profile_id : null,
                'year_started' => $row->year_started ?? null,
                'username'     => $row->username,
                'name'         => $name,
                'email'        => $row->email,
                'role'         => $row->role_name ?? 'Unknown',
                'goals'        => $goalsCount,
                'completedGoals' => $completedGoalsCount,
                'updatedAt'    => $lastUpdated ? Carbon::parse($lastUpdated)->format('Y-m-d') : null,
                'notStarted' => $levels['notStarted'],
                'levels' => $levels['levels'],
            ];
        })->values();

        // Return stats and user info for all users
        return [
            'stats' => [
                'totalUsers' => $users->count(),
                'totalStudents' => $this->countUsersByRole($users, 'Student'),
                'totalStaff' => $this->countUsersByRole($users, 'Staff'),
                'totalAdmins' => $this->countUsersByRole($users, 'Admin'),
                'totalGoals' => (int) $users->sum('goals'),
                'totalCompletedGoals' => (int) $users->sum('completedGoals'),
                'totalIndicators' => $totalIndicators,
            ],
            'users' => $users,
        ];
    }

    private function countUsersByRole($users, string $role): int
    {
        return $users
            ->filter(static fn (array $user): bool => strcasecmp((string) ($user['role'] ?? ''), $role) === 0)
            ->count();
    }


    /**
     * Split overview rows into Student / Staff / Admin lists for the PDF export layout.
     */
    private function groupUsersByRoleForPdf($users): array
    {
        $pick = static function (string $role) use ($users) {
            return $users
                ->filter(static fn (array $user): bool => strcasecmp((string) ($user['role'] ?? ''), $role) === 0)
                ->values();
        };

        return [
            ['title' => 'Students', 'users' => $pick('Student')],
            ['title' => 'Staffs', 'users' => $pick('Staff')],
            ['title' => 'Admins', 'users' => $pick('Admin')],
        ];
    }

    public function createUser(Request $request)
    {
        // Keep validation aligned with the admin create-user form constraints.
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,role_id',
            'username' => 'required|string|max:9|unique:users,username',
            'email' => 'required|email|max:254|unique:users,email',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'password' => 'required|string|min:6',
            'year_started' => 'required_if:role_id,3|nullable|integer|min:2000|max:2100',
        ]);

        $user = User::create([
            'role_id' => (int) $validated['role_id'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            // Store a one-way hash only, never plaintext passwords.
            'password_hash' => Hash::make($validated['password']),
            // New accounts start as active.
            'account_status_id' => 1,
        ]);

        // role_id 3 = Student; profile required for admin view/career-plan links.
        if ((int) $validated['role_id'] === 3) {
            StudentProfile::create([
                'user_id' => $user->user_id,
                'preferred_name' => $validated['first_name'],
                'year_started' => (int) $validated['year_started'],
            ]);
        }

        return response()->json([
            'message' => 'User created successfully.',
            'user' => $user,
        ], 201);
    }

    public function deleteUser(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
    }
}