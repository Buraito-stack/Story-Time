<?php

namespace App\Http\Resources\Admin\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class FullStoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'cover_image'  => $this->getCoverImageUrlAttribute(),
            'views'        => $this->views,
            'author'       => $this->author ? [
                'id'    => $this->author->id,
                'name'  => $this->author->name,
                'email' => $this->author->email,
            ] : null,
            'category'      => $this->category ? [
                'id'   => $this->category->id,
                'name' => $this->category->name,
            ] : null,
            'content'       => $this->content,
            'bookmarked_by' => $this->bookmarks ? $this->bookmarks->pluck('id') : [],
            'created_at'    => $this->created_at ,
            'updated_at'    => $this->updated_at ,
        ];
    }
}
