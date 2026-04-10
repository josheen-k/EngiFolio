<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoalFeedback extends Model
{
    use HasFactory;

    protected $table = 'goal_feedback';
    protected $primaryKey = 'feedback_id';
    protected $fillable = [
        'goal_id',
        'staff_id',
        'feedback_content',
    ];

    public function smartGoal()
    {
        return $this->belongsTo(SmartGoal::class, 'goal_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id', 'user_id');
    }
}
