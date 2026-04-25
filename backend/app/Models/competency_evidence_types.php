<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class competency_evidence_types extends Model
{
    use HasFactory;

    protected $primaryKey = 'entry_level_id';
    protected $fillable = [
        'competency_level'
    ];
}
