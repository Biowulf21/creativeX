<?php

namespace App\Http\Repositories\FollowRepository;

use App\Exceptions\AlreadyFollowingException;
use App\Exceptions\UserNotFoundException;
use App\Models\Follow;
use App\Models\User;


class FollowRepository implements FollowRepositoryInterface
{

    //NOTE: made public in case client needs to know if a user is following another user
    public function isFollowing(int $followerUserId, int $followingUserId)
    {
        $isFollowing = Follow::where('follower_user_id', $followerUserId)
            ->where('following_user_id', $followingUserId)->exists();

        return $isFollowing;
    }




    public function follow(int $followerUserId, int $followingUserId)
    {
        try {

            $isFollowing = $this->isFollowing($followerUserId, $followingUserId);
            if ($isFollowing) throw new AlreadyFollowingException();

            $wasPreviouslyFollowing = Follow::withTrashed(true)
                ->where('follower_user_id', $followerUserId)
                ->where('following_user_id', $followingUserId)
                ->whereNotNull('deleted_at')
                ->first();


            if ($wasPreviouslyFollowing) {
                $wasPreviouslyFollowing->restore();

                $follower = User::find($followerUserId);
                $following = User::find($followingUserId);

                return response()
                    ->json(['message' => 'followed successfully',
                            'follower'=> $follower,
                            'now_following' =>$following]);
            }


            //NOTE: In case of the user spamming the follow and unfollow
            //We can  implement rate limiting
            //read here: https://laravel.com/docs/10.x/rate-limiting#main-content

            $follow = Follow::create([
                'follower_user_id' => $followerUserId,
                'following_user_id' => $followingUserId]);


        } catch (AlreadyFollowingException $e){
            throw $e;

        }
          catch (\Throwable $th) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
        return response()
            ->json(['message' => 'followed successfully',
                    'follower'=> $follow->follower(),
                    'now_following' =>$follow->following()],200);
    }

    public function unfollow(int $followerUserId, int $followingUserId)
    {
        $isFollowing = $this->isFollowing($followerUserId, $followingUserId);
        if (!$isFollowing) throw UserNotFollowingException;

        Follow::where(['follower_user_id' => $followerUserId,
            'following_user_id' => $followingUserId])->delete();

        return response()->json(['message'=> 'Successfully unfollowed the user.'], 200);
    }

    public function getAllFollowers(int $userId)
    {
        $user = User::find($userId);

        if (!$user) throw UserNotFoundException;

        return response()->json(['message'=> 'Successfully received all users followed by this user.',
            'following'=> $user->followers()], 200);
    }

    public function getAllFollowing(int $userId)
    {
        $user = User::find($userId);
        if (!$user) throw UserNotFoundException;

        return response()->json(['message'=> 'Successfully received all users followed
            by this user.', 'following'=> $user->following(), 200]);
    }
}
