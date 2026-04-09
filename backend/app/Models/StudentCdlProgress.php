<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCdlProgress extends Model
{
    use HasFactory;

    protected $table = 'student_cdl_progress';
    protected $primaryKey = 'progress_id';
    protected $fillable = [
        'user_id',
        'cdl_id',
        'status',
        'progress_percentage',
        'last_accessed_at',
        'completed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cdlModule()
    {
        return $this->belongsTo(CdlModule::class, 'cdl_id');
    }
}
