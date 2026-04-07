<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustryContact extends Model
{
    use HasFactory;

    protected $table = 'industry_contacts';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'contact_name',
        'email_id',
        'company_name',
        'date_met',
    ];
}