<?php

namespace App\Http\Controllers\Api\User\V1\Me\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest; 
use App\Http\Resources\User\V1\ProfileResource; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user(); 
        return new ProfileResource($user);
    }

    public function update(UpdateProfileRequest $request)
    {
    $user = Auth::user();

    try {
        $this->updateProfile($user, $request);

        if ($request->filled('current_password') && $request->filled('new_password')) {
            if (!$this->updatePassword($user, $request)) {
                return response()->json(['message' => 'Current password is incorrect.'], 403);
            }
        }

        $user = $user->fresh();

        return response()->json([
            'message' => 'Profile updated successfully.',
            'data' => new ProfileResource($user) 
        ]);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Profile update failed.', 'error' => $e->getMessage()], 500);
    }
    }


    protected function updateProfile($user, $request)
    {
        $updateData = $request->only(['name', 'username', 'email', 'about_me']);
        $user->update($updateData);
    
        if ($request->hasFile('avatar')) {
            $this->handleAvatarUpload($user, $request->file('avatar'));
        }
    }

    protected function handleAvatarUpload($user, $avatar)
    {
        if ($user->profilePicture) {
            \Storage::disk('public')->delete($user->profilePicture->file_path); 
            $user->profilePicture()->delete();
        }

        $avatarPath = $avatar->store('avatars', 'public');

        $user->profilePicture()->create([
            'file_name' => $avatar->getClientOriginalName(),
            'file_path' => $avatarPath,
            'file_size' => $avatar->getSize(),
            'file_type' => $avatar->getMimeType(),
        ]);
    }

    protected function updatePassword($user, $request)
    {
        if (!Hash::check($request->current_password, $user->password)) {
            return false;
        }

        $user->update(['password' => Hash::make($request->new_password)]);
        return true;
    }
}
