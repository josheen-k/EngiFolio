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
        'profile_id',
        'indicator_id',
        'experience_title',
        'associated_year',
        'experience_tasks',
        'key_learnings',
        'future_applications',
        'entry_level_id',
        'entry_status_id',
        'start_date',
        'end_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'profile_id', 'profile_id');
    }

    public function indicator()
    {
        return $this->belongsTo(CompetencyIndicator::class, 'indicator_id', 'indicator_id');
    }

    public function feedback()
    {
        return $this->hasMany(CompetencyFeedback::class, 'entry_id', 'entry_id');
    }

    public function evidence()
    {
        return $this->hasMany(CompetencyEvidence::class, 'entry_id', 'entry_id');
    }

    public function entryLevel()
    {
        return $this->belongsTo(CompetencyEntryLevel::class, 'entry_level_id', 'entry_level_id');
    }

    public function entryStatus()
    {
        return $this->belongsTo(CompetencyEntryStatus::class, 'entry_status_id', 'entry_status_id');
    }
}
