<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetencyEntryStatus extends Model
{
    use HasFactory;

    protected $table = 'competency_entry_statuses';
    protected $primaryKey = 'entry_status_id';
    protected $fillable = [
        'entry_status'
    ];

    public function entries()
    {
        return $this->hasMany(CompetencyEntry::class, 'entry_status_id', 'entry_status_id');
    }
}
