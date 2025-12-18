<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JhepaAdmin extends Model
{
    protected $table = 'jhepa_admin';

    protected $fillable = [
        'user_id',
        'staff_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
