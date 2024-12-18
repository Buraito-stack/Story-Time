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
    public function store(StoryRequest $request)
    {
        $story              = new Story();
        $story->user_id     = Auth::id();
        $story->title       = $request->title;
        $story->category_id = $request->category;
        $story->content     = $request->content;
        $story->save();

        if ($request->hasFile('cover_image')) {
            foreach ($request->file('cover_image') as $image) {
                $story->uploadPicture($image, 'covers');
            }
        }

        return new StoryResource($story);
    }

    public function update(StoryRequest $request, Story $story)
    {
        $this->authorize('update', $story);

        $story->update($request->only(['title', 'category', 'content']));

        if ($request->hasFile('cover_image')) {
            $story->deletePicture(); 
            foreach ($request->file('cover_image') as $image) {
                $story->uploadPicture($image, 'covers');
            }
        }

        return new StoryResource($story);
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
