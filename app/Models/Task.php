<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'order',
        'due_date',
        'completion_percentage',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    /**
     * Get the project this task belongs to
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get completion status as a percentage
     */
    public function getProgressAttribute()
    {
        return $this->completion_percentage;
    }
}
