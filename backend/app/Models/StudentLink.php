<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLink extends Model
{
    use HasFactory;

    protected $table = 'student_links';
    protected $primaryKey = 'link_id';
    protected $fillable = [
        'profile_id',
        'link_type_id',
        'link_label',
        'link_url',
    ];

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class, 'profile_id', 'profile_id');
    }

    public function linkType()
    {
        return $this->belongsTo(LinkType::class, 'link_type_id', 'link_type_id');
    }
}
