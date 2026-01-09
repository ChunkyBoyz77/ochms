<?php

// app/Models/Resource.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        'title',
        'category',
        'external_link',
        'image_path',
        'description',
        'admin_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(\App\Models\JhepaAdmin::class, 'admin_id');
    }
}

