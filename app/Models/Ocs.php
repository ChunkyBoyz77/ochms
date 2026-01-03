<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ocs extends Model
{
    protected $fillable = [
        'user_id',
        'matric_id',
        'faculty',
        'course',
        'study_year',
        'current_semester',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class);
    }

}

