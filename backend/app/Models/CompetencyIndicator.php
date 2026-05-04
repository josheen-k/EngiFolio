<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetencyIndicator extends Model
{
    use HasFactory;

    protected $table = 'competency_indicators';
    protected $primaryKey = 'indicator_id';
    protected $fillable = [
        'group_id',
        'display_id',
        'description',
        'indicator_link',
        'discontinued_date',
    ];

    public function group()
    {
        return $this->belongsTo(CompetencyGroup::class, 'group_id', 'group_id');
    }

    public function entries()
    {
        return $this->hasMany(CompetencyEntry::class, 'indicator_id', 'indicator_id');
    }

    // Called by the controller to find the highest weighted entry
    public function highestEntry()
    {
        // Return the first entry when joining entries to entry levels and ordering from highest weight first
        return $this->hasOne(CompetencyEntry::class, 'indicator_id', 'indicator_id')
            ->join('competency_entry_levels', 'competency_entries.entry_level_id', '=', 'competency_entry_levels.entry_level_id')
            ->orderByDesc('competency_entry_levels.competency_level_weighting');
    }
}
