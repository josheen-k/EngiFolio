<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\NetworkingEventQuestion;
use App\Models\NetworkingEventComment;
use App\Models\StudentProfile;
use App\Models\IndustryContact;

class NetworkingEvent extends Model
{
    use HasFactory;

    protected $table = 'networking_events';
    protected $primaryKey = 'event_id';
    protected $fillable = [
        'profile_id',
        'event_name',
        'event_datetime',
        'location',
        'details',
    ];

    //get the student profile that owns this networking event
    public function profile()
    {   
        return $this->belongsTo(StudentProfile::class,'profile_id', 'profile_id');
    }

    //get all questions that belong to this networking event
    public function questions()
    {
        return $this->hasMany(NetworkingEventQuestion::class, 'event_id', 'event_id');
    }

    //get all comments that belong to this networking event
    public function comments()
    {
        return $this->hasMany(NetworkingEventComment::class, 'event_id', 'event_id');
    }
    
    //get all industry contacts linked to this networking event
    public function contacts()
    {   
        return $this->belongsToMany(
            IndustryContact::class,
            'networking_event_contacts',
            'event_id',
            'contact_id'
        );
    }
}


