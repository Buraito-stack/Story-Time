<?php

namespace App\Models;

use App\Traits\HasPicture;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory , HasPicture;

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
        return $this->morphMany(File::class, 'fileable');
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
        return $this->coverImage->map(fn($image) => asset('storage/' . $image->file_path))->all();
    }
}
