<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkingEventQuestion extends Model
{
    use HasFactory;

    protected $table = 'networking_event_questions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'event_id',
        'question_text',
        'question_order'
    ];

    public function event()
    {
        return $this->belongsTo(NetworkingEvent::class, 'event_id', 'event_id');
    }
}
