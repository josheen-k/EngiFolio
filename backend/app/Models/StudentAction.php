<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAction extends Model
{
    use HasFactory;

    protected $table = 'student_actions';  
    protected $primaryKey = 'action_id';
    protected $fillable = [
        'profile_id',
        'action',
    ];
    public function profile()
    {
            return $this->belongsTo(StudentProfile::class, 'profile_id', 'profile_id');
    }
}
