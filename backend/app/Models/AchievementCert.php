<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AchievementCert extends Model
{
    use HasFactory;

    protected $table = 'achievement_cert';
    protected $primaryKey = 'achievement_cert_id';

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'file_path',
        'issued_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
