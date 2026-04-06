<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{   
    // view student list
    public function index() {
        return Student::all();
    }

    // create new
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string',
            'student_id' => 'required|string|unique:students,student_id',
            'grade' => 'required|integer|min:0|max:100'
        ]);

        return Student::create($validated);
    }

    // update existing
    public function update(Request $request, Student $student) {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'student_id' => 'sometimes|string|unique:students,student_id,' . $student->id,
            'grade' => 'sometimes|integer|min:0|max:100'
        ]);

        $student->update($validated);
        return $student;
    }

    // delete 
    public function destroy(Student $student) {
        $student->delete();
        return ['message' => 'Deleted successfuly'];
    }

}
