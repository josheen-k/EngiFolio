<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class account_status extends Model
{
    use HasFactory;

    protected $primaryKey = 'account_status_id';
    protected $fillable = [
        'account_status',
    ];
}
