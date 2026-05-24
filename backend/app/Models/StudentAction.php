<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAction extends Model
{
    use HasFactory;

    protected $table = 'student_actions';  
    protected $primaryKey = 'student_action_id';
    protected $fillable = [
        'student_profile_id',
        'action',
    ];
    public function profile()
    {
            return $this->belongsTo(StudentProfile::class, 'student_profile_id', 'profile_id');
    }
}
