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
        'level',
        'start_date',
        'end_date',
        'skill_review',
        'evidence',
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
}
