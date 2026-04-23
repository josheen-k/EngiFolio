<?php

namespace App\Http\Controllers;

use App\Models\AttainmentCert;
use Illuminate\Http\Request;

class AttainmentCertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(AttainmentCert::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AttainmentCert $attainmentCert)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AttainmentCert $attainmentCert)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttainmentCert $attainmentCert)
    {
        //
    }
}
