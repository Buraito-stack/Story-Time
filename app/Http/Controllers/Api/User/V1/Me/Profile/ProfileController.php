<?php

namespace App\Http\Controllers\Api\User\V1\Me\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest; 
use App\Http\Resources\ProfileResource; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user(); 

        return new ProfileResource($user);
    }

    /**
     * Update the authenticated user's profile.
     *
     * @param UpdateProfileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        try {
            $this->updateProfile($user, $request);

            if ($request->filled('current_password') && $request->filled('new_password')) {
                $this->updatePassword($user, $request);
            }

            return response()->json(['message' => 'Profile updated successfully.']);
        } catch (\Exception $e) {
            Log::error('Profile update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Profile update failed.'], 500);
        }
    }

    /**
     * Update the user's basic profile information.
     *
     * @param $user
     * @param $request
     * @return void
     */
    protected function updateProfile($user, $request)
    {
        Log::info('Updating user profile', ['user_id' => $user->id, 'data' => $request->only(['name', 'username', 'email', 'about_me'])]);

        $user->update($request->only(['name', 'username', 'email', 'about_me']));

        if ($request->hasFile('avatar')) {
            $this->handleAvatarUpload($user, $request->file('avatar'));
        }
    }

    /**
     * Handle the avatar upload process.
     *
     * @param $user
     * @param $avatar
     * @return void
     */
    protected function handleAvatarUpload($user, $avatar)
    {
        Log::info('Handling avatar upload for user', ['user_id' => $user->id]);

        if ($user->profilePicture) {
            Log::info('Deleting old avatar', ['file_path' => $user->profilePicture->file_path]);
            $user->profilePicture->delete(); 
        }

        $avatarPath = $avatar->store('avatars', 'public');

        Log::info('Creating new profile picture', ['avatar_path' => $avatarPath]);

        $user->profilePicture()->create([
            'file_path' => $avatarPath,
        ]);
    }

    /**
     * Update the user's password.
     *
     * @param $user
     * @param $request
     * @return void
     */
    protected function updatePassword($user, $request)
    {
        if (!Hash::check($request->current_password, $user->password)) {
            Log::warning('Current password is incorrect', ['user_id' => $user->id]);
            return response()->json(['message' => 'Current password is incorrect.'], 403);
        }

        $user->update(['password' => Hash::make($request->new_password)]);
        Log::info('Password updated successfully', ['user_id' => $user->id]);
    }
}
