<?php

namespace App\Http\Controllers;

use App\Models\CompetencyEntryLevel;
use Illuminate\Http\Request;

class CompetencyEntryLevelsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $levels = CompetencyEntryLevel::orderBy('competency_level_weighting', 'asc')->get();

        return response()->json($levels);
    }

    public function getLevelByWeighting($weight)
    {
        $level = CompetencyEntryLevel::where('competency_level_weighting', $weight)->firstOrFail();

        return response()->json($level);
    }
}
