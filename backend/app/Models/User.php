<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory; 

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'role_id',
        'username',
        'email',
        'first_name',
        'last_name',
        'password_hash',
        // Must match users.account_status_id column name for mass assignment.
        'account_status_id',
    ];

    // Used to protect the hash from being returned for security reasons
    protected $hidden = [
        'password_hash',
    ];

    public function profile()
    {
        return $this->hasOne(StudentProfile::class, 'user_id', 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function getRouteKeyName()
    {
        return 'user_id';
    }
}
