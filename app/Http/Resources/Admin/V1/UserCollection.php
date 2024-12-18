<?php

namespace App\Http\Resources\Admin\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * The resource being collected.
     *
     * @var string
     */
    public static $wrap = 'users'; 

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return UserResource::collection($this->collection)->toArray($request);
    }
}
