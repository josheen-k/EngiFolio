<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class industry_contacts extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'contact_id',
        'contact_name',
        'email_id',
        'company_name',
        'date_met',
    ];
}
