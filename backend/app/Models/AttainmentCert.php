<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttainmentCert extends Model
{
    use HasFactory;

    protected $table = 'attainment_cert';
    protected $primaryKey = 'attainment_cert_id';
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'file_path',
        'issued_date',
        'expiry_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
