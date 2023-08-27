<?php

namespace App\Http\Repositories\FollowRepository;

use App\Models\Follow;


class FollowRepository implements FollowRepositoryInterface
{

    public function isFollowing(int $followingUserId, int $followerUserId)
    {
        $isFollowing = Follow::where('follower_id', $followerUserId)
            ->where('following_id', $followingUserId)->exists();

        return $isFollowing;
    }

}
