<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use function Illuminate\Support\now;

class Payment extends Model
{
    protected $fillable = [
        'project_id',
        'payer_id',
        'amount',
        'payment_method',
        'status',
        'reference_number',
        'notes',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the project this payment is for
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who made this payment
     */
    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    /**
     * Mark payment as completed
     */
    public function markAsCompleted()
    {
        $this->status = 'completed';
        $this->paid_at = now();
        $this->save();
        
        // Update project's paid amount
        $this->project->paid_amount = $this->project->payments()
            ->where('status', 'completed')
            ->sum('amount');
        $this->project->save();
    }
}
