<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\NetworkingEventQuestion;
use App\Models\User;
use App\Models\IndustryContact;

class NetworkingEvent extends Model
{
    use HasFactory;

    protected $table = 'networking_events';
    protected $primaryKey = 'event_id';
    protected $fillable = [
        'user_id',
        'event_name',
        'event_datetime',
        'location',
        'details',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function questions()
    {
        return $this->hasMany(NetworkingEventQuestion::class, 'event_id','event_id');
    }

    public function comments()
    {
        return $this->hasMany(NetworkingEventComment::class, 'event_id', 'event_id');
    }

    public function contacts()
    {
        return $this->belongsToMany(IndustryContact::class, 'networking_event_contacts', 'event_id', 'contact_id');
    }
}


