<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMethodType extends Model
{
    use HasFactory;

    protected $table = 'contact_method_types';
    protected $primaryKey = 'contact_method_type_id';
    protected $fillable = [
        'method_type', 
        'field_size'
    ];

    public function contactMethods()
    {
        return $this->hasMany(ContactMethod::class, 'contact_method_type_id', 'contact_method_type_id');
    }
}
