<?php

namespace App\Http\Controllers\Api\Admin\V1\StoryManagement;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoryResource;
use App\Models\Story;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    /**
     * Display a listing of all stories.
     */
    public function index()
    {
        $stories = Story::with(['author', 'category'])->get();
        
        return StoryResource::collection($stories); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Story $story)
    {
        return new StoryResource($story);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Story $story) 
    {
        $story->delete();
        
        return response()->noContent();
    }
}
