<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AchievementCert extends Model
{
    use HasFactory;

    protected $table = 'achievement_certs';
    protected $primaryKey = 'achievement_cert_id';

    protected $fillable = [
        'profile_id',
        'title',
        'body',
        'file_path',
        'issued_date',
    ];

    public function profile()
    {
        return $this->belongsTo(StudentProfile::class, 'profile_id', 'profile_id');
    }
}
