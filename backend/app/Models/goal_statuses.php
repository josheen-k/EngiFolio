<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class goal_statuses extends Model
{
    use HasFactory;

    protected $primaryKey = 'goal_status_id';
    protected $fillable = [
        'status'
    ];
}
