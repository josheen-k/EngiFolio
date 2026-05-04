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

    public function user()
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }
}
