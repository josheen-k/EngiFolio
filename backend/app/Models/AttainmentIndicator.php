<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttainmentIndicator extends Model
{
    use HasFactory;

    protected $primaryKey = 'attainment_indicator_id';
 
    protected $fillable = [
        'indicator_id',
        'attainment_indicator',
    ];

    public function competencyIndicator(): BelongsTo
    {
        return $this->belongsTo(CompetencyIndicator::class, 'indicator_id', 'indicator_id');
    }

}
