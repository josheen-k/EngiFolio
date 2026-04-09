<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorStudentMapping extends Model
{
    use HasFactory;

    protected $table = 'mentor_student_mapping';
    protected $primaryKey = 'mapping_id';
    protected $fillable = [
        'staff_id',
        'student_id',
        'assigned_at',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id', 'user_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'user_id');
    }
}
