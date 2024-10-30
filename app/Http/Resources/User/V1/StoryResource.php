<?php

namespace App\Http\Resources\User\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Str; 

class StoryResource extends JsonResource
{
    public function toArray($request)
    {
        $bookmarkedStoryIds = $this->additional['bookmarkedStoryIds'] ?? [];
    
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'cover_image'   => $this->getCoverImageUrlAttribute(),
            'user'          => [
                'name'   => $this->author->name,
                'avatar' => $this->author->getAvatarUrlAttribute(),
            ],
            'category'      => [
                'id'   => $this->category->id,
                'name' => $this->category->name,
            ],
            'short_content' => Str::limit($this->content, 100),
            'created_at'    => $this->created_at,
            'is_bookmark'   => in_array($this->id, $bookmarkedStoryIds),
        ];
    }    
}
