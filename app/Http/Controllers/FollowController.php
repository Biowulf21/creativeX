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


}
