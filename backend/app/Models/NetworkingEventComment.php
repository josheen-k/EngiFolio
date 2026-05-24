<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\NetworkingEvent;

class NetworkingEventComment extends Model
{
    use HasFactory;

    protected $table = 'networking_event_comments';

    protected $fillable = [
        'event_id',
        'comment_text',
        'comment_type',
        'link_url',
        'file_path',
        'file_name',
    ];

    public function event()
    {
        return $this->belongsTo(NetworkingEvent::class, 'event_id', 'event_id');
    }
}