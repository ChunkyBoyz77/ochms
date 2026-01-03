<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'listing_id',
        'ocs_id',
        'rating',
        'review',
        'stay_from',
        'stay_until',
    ];

    protected $casts = [
        'rating' => 'integer',
        'stay_from'  => 'date',
        'stay_until' => 'date',
    ];

    /* ================= RELATIONSHIPS ================= */

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function ocs()
    {
        return $this->belongsTo(Ocs::class);
    }
}
