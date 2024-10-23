<?php

namespace App\Http\Controllers\Api\User\V1\Me\StoryManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoryRequest; 
use App\Http\Resources\StoryResource;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;

class StoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stories = Story::with(['author', 'category'])
            ->where('author_id', Auth::id()) 
            ->get(); 
        
        return StoryResource::collection($stories); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoryRequest $request)
    {
        $story = (new Story())->setUser(Auth::user())->fill($request->validated());
        $story->save();
        
        return new StoryResource($story); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws AuthorizationException
     */
    public function update(StoryRequest $request, Story $story) 
    {
        $this->authorize('update', $story);
        
        $story->update($request->validated());
        return new StoryResource($story); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws AuthorizationException
     */
    public function destroy(Story $story) 
    {
        $this->authorize('delete', $story);
        
        $story->delete();
        return response()->noContent(); 
    }
}
