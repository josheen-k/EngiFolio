<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerDevelopmentPlan extends Model
{
    use HasFactory;

    protected $table = 'career_development_plans';

    protected $primaryKey = 'plan_id';

    protected $fillable = [
        'profile_id',
        'plan_year',
        'professional_interests',
        'employers_of_interest',
        'networking_plan',
        'personal_values',
        'extracurriculars',
        'development_focus',
    ];

    public function profile()
    {
        return $this->belongsTo(StudentProfile::class, 'profile_id', 'profile_id');
    }

    public function smartGoals()
    {
        return $this->belongsToMany(
            SmartGoal::class,
            'career_development_plan_smart_goal',
            'plan_id',
            'goal_id',
            'plan_id',
            'goal_id'
        )->withTimestamps();
    }
}
