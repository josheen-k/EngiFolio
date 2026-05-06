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
        'profile_id',
        'contact_name',
        'company',
        'progress_notes',
        'date_met',
    ];

    public function getRouteKeyName()
    {
        return 'contact_id';
    }

    public function profile()
    {
        return $this->belongsTo(StudentProfile::class, 'profile_id', 'profile_id');
    }

    public function contactMethods()
    {
        return $this->hasMany(IndustryContactMethod::class, 'contact_id', 'contact_id');
    }
}
