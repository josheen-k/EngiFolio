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
}
