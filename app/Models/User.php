<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 
        'username', 
        'email', 
        'password', 
        'avatar', 
        'about_me',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'password'          => 'hashed',
        ];
    }

    public function profilePicture()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function stories()
    {
        return $this->hasMany(Story::class, 'author_id');
    }

    public function bookmarks()
    {
        return $this->belongsToMany(Story::class, 'bookmarks');
    }

    public function getAvatarUrlAttribute()
    {
        return $this->profilePicture ? asset($this->profilePicture->file_path) : null;
    }
}
