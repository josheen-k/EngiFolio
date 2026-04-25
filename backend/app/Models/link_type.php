<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class link_type extends Model
{
    use HasFactory;

    protected $primaryKey = 'link_type_id';
    protected $fillable = [
        'link_type'
    ];
}
