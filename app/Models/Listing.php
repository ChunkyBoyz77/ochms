<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $fillable = [
        'landlord_id',
        'ocs_id',
        'last_occupied_ocs_id',
        'title',
        'property_type',
        'bedrooms',
        'bathrooms',
        'beds',
        'max_occupants',
        'description',
        'address',
        'latitude',
        'longitude',
        'distance_to_umpsa',
        'monthly_rent',
        'deposit',
        'amenities',
        'policy_cancellation',
        'policy_refund',
        'policy_early_movein',
        'policy_late_payment',
        'policy_additional',
        'grant_document_path',
        'status',
        'published_at',
    ];

    protected $casts = [
        'amenities' => 'array',
        'monthly_rent' => 'decimal:2',
        'deposit' => 'decimal:2',
        'distance_to_umpsa' => 'float',
    ];

    /* ================= Relationships ================= */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function landlord()
    {
        return $this->belongsTo(\App\Models\Landlord::class, 'landlord_id');
    }


    public function ocs()
    {
        return $this->belongsTo(\App\Models\Ocs::class, 'ocs_id');
    }

    public function images()
    {
        return $this->hasMany(ListingImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class);
    }

    public function averageRating()
    {
        return round($this->reviews()->avg('rating'), 1);
    }


}
