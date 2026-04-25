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
        'plan_id',
        'goal_description',
        'timeline',
        'progress_notes',
        'learnings',
        'start_date',
        'end_date',
        'completion_date',
        'completion_notes',
        'goal_status_id',
    ];

    public function plan()
    {
        return $this->belongsTo(CareerDevelopmentPlan::class, 'plan_id', 'plan_id');
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
