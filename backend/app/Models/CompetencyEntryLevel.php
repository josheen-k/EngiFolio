<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetencyEntryLevel extends Model
{
    use HasFactory;

    protected $table = 'competency_entry_levels'; 
    protected $primaryKey = 'entry_level_id';
    protected $fillable = [
        'competency_level'
    ];

    public function entries()
    {
        return $this->hasMany(CompetencyEntry::class, 'entry_level_id', 'entry_level_id');
    }
}
