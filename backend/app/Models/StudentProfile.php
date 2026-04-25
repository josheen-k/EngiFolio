<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $table = 'student_profiles';
    protected $primaryKey = 'profile_id';
    protected $fillable = [
        'user_id',
        'first_name',
        'preferred_name',
        'last_name',
        'degree_title',
        'specialisation',
        'personal_intro',
        'profile_image_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function links()
    {
        return $this->hasMany(StudentLink::class, 'profile_id');
    }

    public function actions()
    {
        return $this->hasMany(StudentAction::class, 'student_profile_id', 'profile_id');
    }
}
