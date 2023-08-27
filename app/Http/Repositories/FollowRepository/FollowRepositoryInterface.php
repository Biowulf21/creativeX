<?php
namespace App\Http\Repositories\FollowRepository;

interface FollowRepositoryInterface
{
    public function isFollowing(int $followingUserId, int $followerUserId);
    public function follow(int $followingUserId, int $followerUserId);
    public function unfollow(int $followingUserId, int $followerUserId);
    public function getAllFollowers(int $userId);
    public function getAllFollowing(int $userId);
}
