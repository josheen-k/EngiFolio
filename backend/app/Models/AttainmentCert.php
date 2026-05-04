<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttainmentCert extends Model
{
    use HasFactory;

    protected $table = 'attainment_certs';
    protected $primaryKey = 'attainment_cert_id';
    protected $fillable = [
        'profile_id',
        'title',
        'body',
        'file_path',
        'issued_date',
        'expiry_date',
    ];

    public function profile()
    {
        return $this->belongsTo(StudentProfile::class, 'profile_id', 'profile_id');
    }
}
