<?php

namespace App\Http\Controllers;

use App\Models\TestStudent;
use Illuminate\Http\Request;

class TestStudentController extends Controller
{
    // Read all
    public function index()
    {
        return response()->json(TestStudent::all());
    }

    // Create
    public function store(Request $request)
    {
        $student = TestStudent::create($request->all());

        return response()->json($student, 201);
    }

    // Read one
    public function show($id)
    {
        return response()->json(TestStudent::findOrFail($id));
    }

    // Update
    public function update(Request $request, $id)
    {
        $student = TestStudent::findOrFail($id);
        $student->update($request->all());

        return response()->json($student);
    }

    // Delete
    public function destroy($id)
    {
        TestStudent::destroy($id);

        return response()->json(null, 204);
    }
}