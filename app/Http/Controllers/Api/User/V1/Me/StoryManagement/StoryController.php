<?php

namespace App\Http\Controllers\Api\User\V1\Me\StoryManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoryRequest; 
use App\Http\Resources\User\V1\StoryResource;
use App\Models\Story;
use App\Models\File;
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
            $this->uploadFile($request->file('cover_image'), $story);
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

            $story->title       = $request->title;
            $story->category_id = $request->category;
            $story->content     = $request->content;
            $story->save();

            if ($request->hasFile('cover_image')) {
                $this->deleteOldFile($story);
                $this->uploadFile($request->file('cover_image'), $story);
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

        $this->deleteOldFile($story);
        $story->delete();

        return response()->noContent(); 
    } catch (AuthorizationException $e) {
        return response()->json(['message' => 'This action is unauthorized.'], 403);
        }
    }


    /**
     * Upload a file and associate it with the story.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param Story $story
     * @return void
     */
    protected function uploadFile($file, Story $story)
    {
        $path = $file->store('covers');
    
        $fileName = $file->getClientOriginalName(); 
        $fileSize = $file->getSize(); 
        $fileType = $file->getMimeType(); 
        
        $story->coverImage()->create([
            'file_name' => $fileName,
            'file_path' => $path,
            'file_size' => $fileSize,
            'file_type' => $fileType,
        ]);
    }
    
    /**
     * Delete the old file associated with the story.
     *
     * @param Story $story
     * @return void
     */
    protected function deleteOldFile(Story $story)
    {
        $file = $story->coverImage()->first(); 
        
        if ($file) {
            \Storage::disk('public')->delete($file->file_path); 
            $file->delete();
        }
    }
}
