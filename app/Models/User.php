<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\HostelEatsVerifyEmail;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'branch_id',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_admin'          => 'boolean',
            'branch_id'         => 'integer',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isBranchAdmin(): bool
    {
        return $this->role === 'branch_admin';
    }

    public function initials(): string
    {
        $parts = array_filter(explode(' ', trim($this->name)));
        $i = strtoupper(substr($parts[0] ?? 'G', 0, 1));
        if (count($parts) > 1) {
            $i .= strtoupper(substr(end($parts), 0, 1));
        }
        return $i;
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new HostelEatsVerifyEmail);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
