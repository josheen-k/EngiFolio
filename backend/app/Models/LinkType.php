<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkType extends Model
{
    use HasFactory;

    protected $table = 'link_types';
    protected $primaryKey = 'link_type_id';
    protected $fillable = [
        'link_type'
    ];

    public function studentLinks()
    {
        return $this->hasMany(StudentLink::class, 'link_type_id', 'link_type_id');
    }
}
