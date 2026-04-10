<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustryContact extends Model
{
    use HasFactory;

    protected $table = 'industry_contacts';
    protected $primaryKey = 'contact_id';
    protected $fillable = [
        'user_id',
        'contact_name',
        'company',
        'progress_notes',
        'date_met',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contactMethods()
    {
        return $this->hasMany(IndustryContactMethod::class, 'contact_id');
    }
}
