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
        'user_id',
        'plan_year',
        'professional_interests',
        'employers_of_interest',
        'networking_plan',
        'personal_values',
        'extracurriculars',
        'development_focus',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function smartGoals()
    {
        return $this->hasMany(SmartGoal::class, 'plan_id');
    }
}
