<?php

namespace App\Http\Controllers;

use App\Models\MentorStudentMapping;
use Illuminate\Http\Request;

class MentorStudentMappingController extends Controller
{
    public function index(Request $request)
    {
        // temporary until auth
        $staffId = $request->query('staff_id', 4);

        $students = MentorStudentMapping::with('profile.user')
            ->where('staff_id', $staffId)
            ->get()
            ->map(function ($mapping) {

                return [
                    'profile_id' => $mapping->profile->profile_id,
                    'user_id' => $mapping->profile->user->user_id,
                    'first_name' => $mapping->profile->user->first_name,
                    'last_name' => $mapping->profile->user->last_name,
                    'email' => $mapping->profile->user->email,
                    'specialisation' => $mapping->profile->specialisation,
                    'degree_title' => $mapping->profile->degree_title,
                ];
            });

        return response()->json($students);
    }
}