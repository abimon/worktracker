<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    protected $table = 'feedbacks';
    protected $fillable = [
        'project_id',
        'user_id',
        'message',
        'rating',
        'type',
        'attachment',
        'resolved',
    ];

    protected $casts = [
        'resolved' => 'boolean',
    ];

    /**
     * Get the project this feedback is for
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who gave this feedback
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark as resolved
     */
    public function markAsResolved()
    {
        $this->resolved = true;
        $this->save();
    }
}
