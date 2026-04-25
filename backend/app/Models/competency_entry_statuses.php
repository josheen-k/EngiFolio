<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class competency_entry_statuses extends Model
{
    use HasFactory;

    protected $primaryKey = 'entry_status_id';
    protected $fillable = [
        'entry_status'
    ];
}
