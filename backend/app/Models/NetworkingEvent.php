<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id', 'profile_id');
    }

    public function questions()
    {
        return $this->hasMany(NetworkingEventQuestion::class, 'event_id', 'event_id');
    }
}
