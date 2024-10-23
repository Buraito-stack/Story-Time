<?php

namespace App\Http\Controllers\Api\User\V1\Bookmark;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoryResource; 
use App\Models\Story;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $bookmarkedStories = $user->bookmarks()->with(['author', 'category', 'coverImage'])->get();

        return StoryResource::collection($bookmarkedStories);
    }
    public function bookmark(Story $story)
    {
        $user = Auth::user(); 

        $user->bookmarks()->attach($story->id);

        return response()->json(['message' => 'Story bookmarked successfully.']);
    }

    public function destroy(Story $story)
    {
        $user = Auth::user(); 
    
        $user->bookmarks()->detach($story->id);
    
        return response()->noContent(); 
    }
}
