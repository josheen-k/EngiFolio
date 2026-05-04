<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountStatus extends Model
{
    use HasFactory;

    protected $table = 'account_statuses';
    protected $primaryKey = 'account_status_id';

    protected $fillable = [
        'account_status',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'account_status_id', 'account_status_id');
    }
}
