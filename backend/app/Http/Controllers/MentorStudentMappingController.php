<?php

namespace App\Http\Controllers;

use App\Models\MentorStudentMapping;
use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MentorStudentMappingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    // gets students mapped to given staff id
    public function getMappedStudents($staffID)
    {
        return response()->json(MentorStudentMapping::where('staff_id',$staffID)->get());
    }

    // gets staff mapped to given student (profile) id
    public function getMappedStaff($profileID)
    {
        return response()->json(MentorStudentMapping::where('profile_id',$profileID)->get());
    }

    // update staff mapped to given student (profile) id
    public function updateMappedStaff(Request $request, $profileID)
    {
        // check mapping with given student (profile_id) exists
        $mapping = MentorStudentMapping::where('profile_id','=', $profileID)->get();
        if ($goalFeedback->isEmpty()) {
            return response()->json(['message' => 'Error: Student (profile_id) not found'], 404);
        }
        if ($goalFeedback->first() != $goalFeedback){
            return response()->json(['message' => 'Error: Given student (profile_id) has more than 1 mapping']);
        }

        // validate request
        $validated = $request->validate([
            'staff_id' => [
                'required',
                Rule::exists('users','user_id')->where(function (Builder $query){
                    $query->where('role_id',2);
                }),
            ],
            'profile_id' => 'required|exists:student_profiles,profile_id',
            'assigned_at' => 'nullable|date',
        ]);

        // update
        $mapping->update([
            'staff_id' => $validated['staff_id'],

        ]);

        return response()->json($mapping);
    }

    public function destroyMappedStaff($profileID)
    {
        // check mapping with given student (profile_id) exists
        $mapping = MentorStudentMapping::where('profile_id','=', $profileID)->get();
        if ($goalFeedback->isEmpty()) {
            return response()->json(['message' => 'Error: Student (profile_id) not found'], 404);
        }
        if ($goalFeedback->first() != $goalFeedback){
            return response()->json(['message' => 'Error: Given student (profile_id) has more than 1 mapping']);
        }

        // update
        $mapping->delete();

        return response()->json(['message' => 'Mapping deleted successfully']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => [
                'required',
                Rule::exists('users','user_id')->where(function (Builder $query){
                    $query->where('role_id',2);
                }),
            ],
            'profile_id' => [
                'required',
                'exists:student_profiles,profile_id',
                'unique:mentor_student_mapping,profile_id'
            ],
            'assigned_at' => 'nullable|date',
        ]);

        return response()->json($validated, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MentorStudentMapping $mentorStudentMapping)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $mappingID)
    {
        //
        $mapping = MentorStudentMapping::findOrFail($mappingID);

        $validated = $request->validate([
            'staff_id' => [
                'required',
                Rule::exists('users','user_id')->where(function (Builder $query){
                    $query->where('role_id',2);
                }),
            ],
            'profile_id' => 'required|exists:student_profiles,profile_id',
            'assigned_at' => 'nullable|date',
        ]);

        $mapping->update($validated);

        return response()->json($mapping);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($mappingID)
    {
        //
        $mapping = MentorStudentMapping::findOrFail($mappingID);
        $mapping->delete();

        return response()->json(['message' => 'Mapping deleted successfully']);
    }
}
