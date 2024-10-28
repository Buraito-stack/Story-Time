<?php

namespace App\Http\Controllers\Api\Admin\V1\StoryManagement;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\V1\StoryResource;
use App\Http\Resources\Admin\V1\FullStoryResource;
use App\Models\Story;

class StoryController extends Controller
{
    public function index()
    {
        $stories = Story::with(['author', 'category', 'coverImage'])->paginate(10);

        return StoryResource::collection($stories);
    }
    
    public function show(Story $story)
    { 
        $story->load(['author', 'category']);
    
        return new FullStoryResource($story);
    }
    
    public function destroy(Story $story) 
    {
        $story->delete();
        
        return response()->noContent();
    }
}
