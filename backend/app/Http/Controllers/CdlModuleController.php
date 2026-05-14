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
        $modules = CdlModule::select([
            'cdl_id',
            'title',
            'description',
            'module_url',
            'updated_at',
        ])->orderBy('updated_at', 'desc')->get();

        return response()->json($modules);    
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
