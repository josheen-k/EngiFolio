<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CdlModule extends Model
{
    use HasFactory;

    protected $table = 'cdl_modules';
    protected $primaryKey = 'cdl_id';
    protected $fillable = [
        'title',
        'description',
        'module_url',
    ];

    public function studentProgress()
    {
        return $this->hasMany(StudentCdlProgress::class, 'cdl_id');
    }
}
