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
        $follower_id = $request->follower_id;
        $following_id = $request->following_id;

        return $this->follow_repository->isFollowing($following_id, $follower_id);

    }


    public function followUser(FollowRequest $request)
    {
        $follower_id = $request->follower_id;
        $following_id = $request->following_id;

        // TODO: implement checking if the user being followed allows
        // their account to be followed
        return $this->follow_repository->follow($following_id, $follower_id);
    }

    // FollowRequest has the same parameters needed as an UnfollowRequest
    // Decided not to make another one because of that
    public function unfollowUser(FollowRequest $request)
    {
        $follower_id = $request->follower_id;
        $following_id = $request->following_id;

        return $this->follow_repository->unfollow($following_id, $follower_id);

    }



}
