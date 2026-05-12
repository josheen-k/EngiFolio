<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MentorStudentMapping extends Model
{
    protected $table = 'mentor_student_mapping';
    protected $primaryKey = 'mapping_id';

    protected $fillable = [
        'staff_id',
        'profile_id',
        'assigned_at',
    ];

    public function profile()
    {
        return $this->belongsTo(StudentProfile::class, 'profile_id', 'profile_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id', 'user_id');
    }
}