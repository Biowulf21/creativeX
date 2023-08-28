<?php

namespace App\Http\Controllers;

use App\Http\Repositories\FollowRepository\FollowRepositoryInterface;
use App\Http\Requests\FollowRequest;

class FollowController extends Controller
{
    private $follow_repository;

    public function __construct(FollowRepositoryInterface $follow_repository)
    {
        $this->follow_repository = $follow_repository;
    }

    public function isFollowing(FollowRequest $request)
    {
        $follower_user_id = $request->follower_id;
        $following_user_id = $request->following_id;

        return $this->follow_repository->isFollowing($following_user_id, $follower_user_id);

    }

    public function followUser(int $follower_user_id, int $following_user_id)
    {

        // TODO: implement checking if the user being followed allows
        // their account to be followed
        return $this->follow_repository->follow($follower_user_id, $following_user_id);
    }

    public function unfollowUser(int $follower_user_id, int $following_user_id)
    {

        return $this->follow_repository->unfollow($follower_user_id, $following_user_id);
    }

    public function getFollowers(int $user_id)
    {
        return $this->follow_repository->getAllFollowers($user_id);
    }

    // $following_user_id represents the user who follows other people
    public function getFollowings(int $user_id)
    {
        return $this->follow_repository->getAllFollowing($user_id);

    }
}
