<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetencyGroup extends Model
{
    use HasFactory;

    protected $table = 'competency_groups';
    protected $primaryKey = 'group_id';
    protected $fillable = [
        'display_id',
        'group_name',
        'description',
        'discontinued_date',
    ];

    public function indicators()
    {
        return $this->hasMany(CompetencyIndicator::class, 'group_id');
    }
}
