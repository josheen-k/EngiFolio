<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
    protected $fillable = ['name', 'date', 'location', 'details'];
    public function questions() {
    return $this->hasMany(\App\Models\EventQuestion::class);
}
}

