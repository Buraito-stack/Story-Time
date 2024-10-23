<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Str; 

class StoryResource extends JsonResource
{
    public function toArray($request)
    {
        $user = Auth::user();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'short_content' => Str::limit($this->content, 100), 
            'user' => [
                'name' => $this->author->name,
                'avatar' => $this->author->getAvatarUrlAttribute(),
            ],
            'category' => [
                'name' => $this->category->name,
            ],
            'cover_image' => $this->getCoverImageUrlAttribute(), 
            'created_at' => $this->created_at,
            'is_bookmark' => $user ? $user->bookmarks()->where('id', $this->id)->exists() : false, 
        ];
    }
}
