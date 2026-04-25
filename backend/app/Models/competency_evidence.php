<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class competency_evidence extends Model
{
    use HasFactory;

    protected $table = 'competency_evidence';
    protected $primaryKey = 'evidence_id';
    protected $fillable = [
        'entry_id', 
        'evidence_type_id', 
        'evidence_value'
    ];

    public function type()
    {
        return $this->belongsTo(CompetencyEvidenceType::class, 'evidence_type_id');
    }

    public function entry()
    {
        return $this->belongsTo(CompetencyEntry::class, 'entry_id');
    }
}
