<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role'              => Role::class,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === Role::Admin;
    }

    public function isStaff(): bool
    {
        return $this->role === Role::Staff;
    }

    public function isUser(): bool
    {
        return $this->role === Role::User;
    }

    public function isStaffOrAbove(): bool
    {
        return $this->isAdmin() || $this->isStaff();
    }
}
