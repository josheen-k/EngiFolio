<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustryContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'contact_name',
        'email_id',
        'company_name',
        'date_met',
    ];
}