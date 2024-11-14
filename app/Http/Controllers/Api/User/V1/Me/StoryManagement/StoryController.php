<?php

namespace App\Http\Controllers\Api\User\V1\Me\StoryManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoryRequest; 
use App\Http\Resources\User\V1\StoryResource;
use App\Models\Story;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;

class StoryController extends Controller
{
    /**
     * Display a listing of the user's stories.
     */
    public function index()
    {
        $stories = Story::with(['author', 'category']) 
            ->where('user_id', Auth::id())
            ->paginate(4);
        
        return StoryResource::collection($stories);
    }

    /**
     * Store a newly created story.
     */
    public function store(StoryRequest $request)
    {
        $story              = new Story();
        $story->user_id     = Auth::id(); 
        $story->title       = $request->title; 
        $story->category_id = $request->category; 
        $story->content     = $request->content;
        $story->save(); 
    
        if ($request->hasFile('cover_image')) {
            $story->manageCoverImages($request->file('cover_image'), 'covers');
        }
        
        return new StoryResource($story); 
    }
    
    /**
    * Update the specified story.
    *
    * @throws AuthorizationException
    */
    public function update(StoryRequest $request, Story $story)
    {
        try {
            $this->authorize('update', $story);
    
            $story->update($request->only(['title', 'category', 'content']));

            if ($request->hasFile('cover_image')) {
                $story->manageCoverImages($request->file('cover_image'), 'covers');
            }
               
            return new StoryResource($story);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'This action is unauthorized.'], 403);
        }
    }
    
    /**
    * Remove the specified story.
    *
    * @throws AuthorizationException
    */
    public function destroy(Story $story) 
    {
        try {
            $this->authorize('delete', $story);

            $story->deletePicture(); 
            $story->delete();

            return response()->noContent(); 
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'This action is unauthorized.'], 403);
        }
    }
}
