<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetencyFeedback extends Model
{
    use HasFactory;

    protected $table = 'competency_feedback';
    protected $primaryKey = 'feedback_id';
    protected $fillable = [
        'entry_id',
        'staff_id',
        'feedback_content',
    ];

    public function competencyEntry()
    {
        return $this->belongsTo(CompetencyEntry::class, 'entry_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id', 'user_id');
    }
}
