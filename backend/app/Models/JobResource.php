<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobResource extends Model
{
    use HasFactory;

    protected $table = 'job_resources';
    protected $primaryKey = 'job_resources_id';
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'file_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
