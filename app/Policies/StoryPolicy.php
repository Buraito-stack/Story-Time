<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Story;

class StoryPolicy
{
    public function update(User $user, Story $story)
    {
        return $user->id === $story->user_id; 
    }

    public function delete(User $user, Story $story)
    {
        return $user->id === $story->user_id; 
    }
}
