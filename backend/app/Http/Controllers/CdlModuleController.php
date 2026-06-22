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
        //select only the fields the frontend CDL page needs 
        $modules = CdlModule::select([
            'cdl_id',
            'title',
            'description',
            'module_url',
            'updated_at',
        ])
        //show the most recently updated modules first 
        ->orderBy('updated_at', 'desc')->get();
        //return the module list as JSON for the frontend 
        return response()->json($modules);    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //this function is currently empty and not being used 
    }

    /**
     * Display the specified resource.
     */
    public function show(CdlModule $cdlModule)
    {
        //this function is currently empty and not being used 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CdlModule $cdlModule)
    {
        //this function is currently empty and not being used
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CdlModule $cdlModule)
    {
        //this function is currently empty and not being used
    }
}
