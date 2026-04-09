<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'role_id',
        'username',
        'email',
        'password_hash',
        'account_status',
    ];

    protected $hidden = ['password_hash',];

    public function profile()
    {
        return $this->hasOne(StudentProfile::class, 'user_id');
    }
}
