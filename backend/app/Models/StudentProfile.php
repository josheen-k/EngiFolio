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
        'preferred_name',
        'degree_title',
        'specialisation',
        'personal_intro',
        'profile_image_url',
        'year_started',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function elevatorPitch()
    {
        return $this->hasOne(ElevatorPitch::class, 'profile_id', 'profile_id');
    }


    public function careerPlans()
    {
        return $this->hasMany(CareerDevelopmentPlan::class, 'profile_id', 'profile_id');
    }

    public function industryContacts()
    {
        return $this->hasMany(IndustryContact::class, 'profile_id', 'profile_id');
    }

    public function competencyEntries()
    {
        return $this->hasMany(CompetencyEntry::class, 'profile_id', 'profile_id');
    }

    public function achievementCerts()
    {
        return $this->hasMany(AchievementCert::class, 'profile_id', 'profile_id');
    }

    public function attainmentCerts()
    {
        return $this->hasMany(AttainmentCert::class, 'profile_id', 'profile_id');
    }

    public function portfolioNotes()
    {
        return $this->hasMany(PortfolioNote::class, 'profile_id', 'profile_id');
    }

    public function jobResources()
    {
        return $this->hasMany(JobResource::class, 'profile_id', 'profile_id');
    }

    public function links()
    {
        return $this->hasMany(StudentLink::class, 'profile_id', 'profile_id');
    }

    public function actions()
    {
        return $this->hasMany(StudentAction::class, 'student_profile_id', 'profile_id');
    }

    public function smartGoals()
    {
        return $this->hasMany(SmartGoal::class, 'profile_id', 'profile_id');
    }
}
