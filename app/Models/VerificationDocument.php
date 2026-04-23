<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use function Illuminate\Support\now;

class VerificationDocument extends Model
{
    protected $fillable = [
        'user_id',
        'document_type',
        'document_url',
        'status',
        'rejection_reason',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    /**
     * Get the user this document belongs to
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Approve the document
     */
    public function approve()
    {
        $this->status = 'approved';
        $this->verified_at = now();
        $this->save();
    }

    /**
     * Reject the document
     */
    public function reject($reason)
    {
        $this->status = 'rejected';
        $this->rejection_reason = $reason;
        $this->save();
    }
}
