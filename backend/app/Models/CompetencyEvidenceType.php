<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetencyEvidenceType extends Model
{
    use HasFactory;

    protected $table = 'competency_evidence_types';
    protected $primaryKey = 'evidence_type_id';
    protected $fillable = [
        'evidence_type',
    ];

    public function evidence()
    {
        return $this->hasMany(CompetencyEvidence::class, 'evidence_type_id', 'evidence_type_id');
    }
}
