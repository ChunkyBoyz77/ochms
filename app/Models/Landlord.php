<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Landlord extends Model
{
    protected $table = 'landlord';

    protected $fillable = [
        'user_id',
        'ic_number',
        'address',
        'phone',
        'ic_pic',
        'bankAccount_num',
        'proof_of_address',
        'screening_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

