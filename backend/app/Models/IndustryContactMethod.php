<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustryContactMethod extends Model
{
    use HasFactory;

    protected $table = 'industry_contact_methods';
    protected $primaryKey = 'method_id';
    protected $fillable = [
        'contact_id',
        'method_type_id',
        'method_value',
    ];

    public function industryContact()
    {
        return $this->belongsTo(IndustryContact::class, 'contact_id', 'contact_id');
    }

    public function methodType()
    {
        return $this->belongsTo(ContactMethodType::class, 'method_type_id', 'contact_method_type_id');
    }
}
