<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class TestStudent extends Model
{
    protected $table = 'test_students';

    protected $fillable = [
        'name',
        'email',
        'age',
    ];
}