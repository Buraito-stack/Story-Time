<?php

namespace App\Http\Resources\User\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth; 

class FullStoryResource extends JsonResource
{
    public function toArray($request)
    {
        $user = Auth::user(); 

        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'cover_image' => $this->getCoverImageUrlAttribute(),
            'user'        => [
                'name'   => $this->author->name, 
                'avatar' => $this->author->getAvatarUrlAttribute(),
            ],
            'category'    => [
                'id'   => $this->category->id,
                'name' => $this->category->name, 
            ], 
            'content'     => $this->content,
            'created_at'  => $this->created_at,
            'is_bookmark' => $user ? $user->bookmarks()->where('id', $this->id)->exists() : false,
        ];
    }
}
