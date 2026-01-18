<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landlord extends Model
{
    use HasFactory;

    /**
     * Table name (explicit because it's singular: landlord)
     */
    protected $table = 'landlord';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'user_id',
        'reviewed_by_admin_id',

        // Documents (binary)
        'ic_pic',
        'supporting_document',

        // Financial
        'bank_account_num',
        'bank_name',

        // Screening
        'has_criminal_record',
        'criminal_record_details',
        'agreement_accepted',

        // Status
        'screening_status',
        'screening_notes',
        'screening_submitted_at',
        'screening_reviewed_at',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'has_criminal_record'     => 'boolean',
        'agreement_accepted'      => 'boolean',
        'screening_submitted_at'  => 'datetime',
        'screening_reviewed_at'   => 'datetime',
    ];

    /**
     * =====================
     * Relationships
     * =====================
     */

    /**
     * The landlord belongs to a user (the account owner)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    /**
     * Admin who reviewed the landlord
     */
    public function reviewedByAdmin()
    {
        return $this->belongsTo(User::class, 'reviewed_by_admin_id');
    }

    /**
     * =====================
     * Helper Methods
     * =====================
     */

    public function isApproved(): bool
    {
        return $this->screening_status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->screening_status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->screening_status === 'rejected';
    }

    /**
     * Check if all required documents are uploaded
     */
    public function hasSubmittedDocuments(): bool
    {
        return !is_null($this->ic_pic)
            && !is_null($this->supporting_document)
            && !is_null($this->bank_account_num)
            && $this->agreement_accepted === true;
    }
}


