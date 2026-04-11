<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoalActionStep extends Model
{
    use HasFactory;

    protected $table = 'goal_action_steps';
    protected $primaryKey = 'step_id';
    protected $fillable = [
        'goal_id',
        'step_order',
        'step_description',
    ];

    public function smartGoal()
    {
        return $this->belongsTo(SmartGoal::class, 'goal_id');
    }
}
