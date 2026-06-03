<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElevatorPitch extends Model
{
    use HasFactory;

    protected $table = 'elevator_pitches';
    protected $primaryKey = 'pitch_id';
    protected $fillable = [
        'profile_id',
        'pitch_text',
    ];

    //get the profile that owns this elevator pitch
    public function user()
    {   
        //this elevator pitch belongs to one profile, linked by profile_id
        return $this->belongsTo(StudentProfile::class, 'profile_id');
    }
}
