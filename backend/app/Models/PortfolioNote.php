<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioNote extends Model
{
    use HasFactory;

    protected $table = 'portfolio_note';
    protected $primaryKey = 'portfolio_note_id';

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'file_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
