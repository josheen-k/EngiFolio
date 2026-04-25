<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contact_method_type extends Model
{
    use HasFactory;

    protected $primaryKey = 'contact_method_type_id';
    protected $fillable = [
        'method_type', 
        'field_size'
    ];
}
