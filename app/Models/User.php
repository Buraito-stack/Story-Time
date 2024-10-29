<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 
        'username', 
        'email', 
        'password', 
        'avatar', 
        'about_me',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    public function profilePicture()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function stories()
    {
        return $this->hasMany(Story::class, 'user_id');
    }

    public function bookmarks()
    {
        return $this->belongsToMany(Story::class, 'bookmarks');
    }

    public function getAvatarUrlAttribute()
    {
        return $this->profilePicture ? asset('storage/' . $this->profilePicture->file_path) : null;
    }
    
    // Mutator
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }
}
