<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'account_status',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'admin_id');
    }

    public function isPelanggan(): bool
    {
        return $this->role === 'pelanggan';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isVerified(): bool
    {
        return $this->account_status === 'verified';
    }
}
