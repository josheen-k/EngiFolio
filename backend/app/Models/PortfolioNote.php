<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioNote extends Model
{
    use HasFactory;

    protected $table = 'portfolio_notes';
    protected $primaryKey = 'portfolio_note_id';

    protected $fillable = [
        'profile_id',
        'title',
        'body',
        'file_path',
    ];

    public function profile()
    {
        return $this->belongsTo(StudentProfile::class, 'profile_id', 'profile_id');
    }
}
