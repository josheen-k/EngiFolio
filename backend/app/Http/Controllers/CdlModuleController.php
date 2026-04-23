<?php

namespace App\Http\Controllers;

use App\Models\CdlModule;
use Illuminate\Http\Request;

class CdlModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $module = CdlModuleProfile::with('studentProgress')->get();

        return response()->json($module);     
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
    public function show(CdlModule $cdlModule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CdlModule $cdlModule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CdlModule $cdlModule)
    {
        //
    }
}
