<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetencyEntry extends Model
{
    use HasFactory;

    protected $table = 'competency_entries';
    protected $primaryKey = 'entry_id';
    protected $fillable = [
        'user_id',
        'indicator_id',
        'experience_title',
        'associated_year',
        'experience_tasks',
        'key_learnings',
        'future_applications',
        'level',
        'status',
        'start_date',
        'end_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function indicator()
    {
        return $this->belongsTo(CompetencyIndicator::class, 'indicator_id');
    }

    public function feedback()
    {
        return $this->hasMany(CompetencyFeedback::class, 'entry_id');
    }
    public function evidence()
    {
        return $this->hasMany(CompetencyEvidence::class, 'entry_id');
    }
}
