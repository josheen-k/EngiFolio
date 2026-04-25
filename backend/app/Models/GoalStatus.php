<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoalStatus extends Model
{
    use HasFactory;

    protected $table = 'goal_statuses';
    protected $primaryKey = 'goal_status_id';
    protected $fillable = [
        'goal_status'
    ];

    public function goals()
    {
        return $this->hasMany(SmartGoal::class, 'goal_status_id', 'goal_status_id');
    }
}
