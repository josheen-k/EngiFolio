<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetencyEvidence extends Model
{
    use HasFactory;

    protected $table = 'competency_evidences';
    protected $primaryKey = 'evidence_id';
    protected $fillable = [
        'entry_id', 
        'evidence_type', 
        'evidence_value'
    ];

    public function entry()
    {
        return $this->belongsTo(CompetencyEntry::class, 'entry_id', 'entry_id');
    }
}
