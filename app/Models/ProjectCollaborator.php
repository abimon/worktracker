<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

use function Illuminate\Support\now;

class ProjectCollaborator extends Model
{
    protected $table = 'project_collaborators';

    protected $fillable = [
        'project_id',
        'user_id',
        'role',
        'invite_token',
        'status',
        'accepted_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];

    /**
     * Get the project
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the collaborator user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate invite token
     */
    public function generateInviteToken()
    {
        $this->invite_token = Str::random(32);
        $this->save();
        return $this->invite_token;
    }

    /**
     * Accept the invitation
     */
    public function accept()
    {
        $this->status = 'accepted';
        $this->accepted_at = now();
        $this->save();
    }

    /**
     * Decline the invitation
     */
    public function decline()
    {
        $this->status = 'declined';
        $this->save();
    }
}
