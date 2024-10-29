<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'cover_image', 
        'content', 
        'views'
    ];

    protected $hidden = [
        'views',
    ];

    public function coverImage()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function bookmarks()
    {
        return $this->belongsToMany(User::class, 'bookmarks');
    }

    public function getCoverImageUrlAttribute()
    {
        return $this->coverImage ? asset('storage/' . $this->coverImage->file_path) : null;
    }
}
