<?php

namespace App\Http\Controllers\Api\Admin\V1\StoryManagement;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\V1\StoryResource;
use App\Http\Resources\Admin\V1\FullStoryResource;
use App\Models\Story;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    public function index(Request $request)
    {
        $sortBy   = $request->input('sort_by', 'newest'); 
        $category = $request->input('category', null); 
        $search   = $request->input('search', null); 
    
        $query = Story::query();
    
        if ($category) {
            $query->where('category_id', $category);
        }
    
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%');
            });
        }
    
        switch ($sortBy) {
            case 'popular':
                $query->orderBy('views', 'desc'); 
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'asc':
                $query->orderBy('title', 'asc');
                break;
            case 'desc':
                $query->orderBy('title', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
    
        $stories = $query->with(['author.profilePicture', 'category'])
                          ->paginate(10)
                          ->withQueryString(); 
    
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
