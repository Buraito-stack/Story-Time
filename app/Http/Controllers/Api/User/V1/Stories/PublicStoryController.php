<?php

namespace App\Http\Controllers\Api\User\V1\Stories;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\V1\FullStoryResource;
use App\Http\Resources\User\V1\StoryResource;
use App\Models\Story;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicStoryController extends Controller
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

    /**
     * Display the specified resource.
     */
    public function show(Story $story) 
    {
        $story->increment('views');
        
        return new FullStoryResource($story);
    }
}
