<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = [
        'developer_id',
        'name',
        'description',
        'budget',
        'paid_amount',
        'status',
        'progress',
        'progress_percentage',
        'deadline',
        'start_date',
        'preview_url',
        'share_token',
    ];

    protected $casts = [
        'deadline' => 'date',
        'start_date' => 'date',
        'budget' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    /**
     * Get the developer who created this project
     */
    public function developer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'developer_id');
    }

    /**
     * Get all tasks for this project
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get all payments for this project
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get all collaborators on this project
     */
    public function collaborators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_collaborators', 'project_id', 'user_id')
                    ->withPivot('role', 'status', 'invite_token', 'accepted_at')
                    ->withTimestamps();
    }

    /**
     * Get all feedbacks for this project
     */
    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class, 'project_id','id');
    }

    /**
     * Get total amount paid
     */
    public function getTotalPaidAttribute()
    {
        return $this->payments()->where('status', 'completed')->sum('amount');
    }

    /**
     * Get remaining balance
     */
    public function getRemainingBalanceAttribute()
    {
        return $this->budget - $this->total_paid;
    }

    /**
     * Generate share token for clients
     */
    public function generateShareToken()
    {
        $this->share_token = Str::random(32);
        $this->save();
        return $this->share_token;
    }
}
