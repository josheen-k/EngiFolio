<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class competency_entry_levels extends Model
{
    use HasFactory;

    protected $primaryKey = 'evidence_type_id';
    protected $fillable = [
        'evidence_type'
    ];
}
