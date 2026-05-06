<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobResource extends Model
{
    use HasFactory;

    protected $table = 'job_resources';
    protected $primaryKey = 'job_resource_id';
    protected $fillable = [
        'profile_id',
        'title',
        'body',
        'file_path',
    ];

    public function profile()
    {
        return $this->belongsTo(StudentProfile::class, 'profile_id', 'profile_id');
    }
}
