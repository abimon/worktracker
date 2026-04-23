<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// class User extends Authenticatable
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'bio',
        'profile_picture',
        'account_type',
        'is_verified',
        'verified_at',
        'wallet_balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
            'wallet_balance' => 'decimal:2',
        ];
    }

    /**
     * Get projects created by this user
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'developer_id');
    }

    /**
     * Get projects this user collaborates on
     */
    public function collaboratedProjects()
    {
        return $this->belongsToMany(Project::class, 'project_collaborators', 'user_id', 'project_id')
                    ->withPivot('role', 'status', 'invite_token', 'accepted_at');
    }

    /**
     * Get payments made by this user
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'payer_id');
    }

    /**
     * Get verification documents
     */
    public function verificationDocuments()
    {
        return $this->hasMany(VerificationDocument::class);
    }

    /**
     * Get feedbacks provided by this user
     */
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
}
