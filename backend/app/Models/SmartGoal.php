<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartGoal extends Model
{
    use HasFactory;

    protected $table = 'smart_goals';

    protected $primaryKey = 'goal_id';

    protected $fillable = [
        'profile_id',
        'goal_description',
        'timeline',
        'progress_notes',
        'learnings',
        'start_date',
        'end_date',
        'completion_date',
        'completion_notes',
        'goal_status_id',
        // Used by manual drag-and-drop ordering on the SMART Goals page.
        'goal_order',
    ];

    public function profile()
    {
        return $this->belongsTo(StudentProfile::class, 'profile_id', 'profile_id');
    }

    public function plans()
    {
        return $this->belongsToMany(
            CareerDevelopmentPlan::class,
            'career_development_plan_smart_goal',
            'goal_id',
            'plan_id',
            'goal_id',
            'plan_id'
        )->withTimestamps();
    }

    public function actionSteps()
    {
        return $this->hasMany(GoalActionStep::class, 'goal_id', 'goal_id');
    }

    public function feedback()
    {
        return $this->hasMany(GoalFeedback::class, 'goal_id', 'goal_id');
    }

    public function status()
    {
        return $this->belongsTo(GoalStatus::class, 'goal_status_id', 'goal_status_id');
    }
}
