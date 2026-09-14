<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
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
        'phone',
        'google_id',
        'avatar',
        'password',
        'role',
        'email_verified_at',
    ];

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            if (\Illuminate\Support\Str::startsWith($this->avatar, ['http://', 'https://'])) {
                return $this->avatar;
            }
            return \Illuminate\Support\Facades\Storage::url($this->avatar);
        }
        $cleanName = preg_replace('/[^a-zA-Z0-9\s]/', '', $this->name);
        return 'https://ui-avatars.com/api/?name=' . urlencode(trim($cleanName)) . '&background=0284c7&color=ffffff&bold=true';
    }

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

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPendaftar(): bool
    {
        return $this->role === 'pendaftar';
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'user_id');
    }
}
