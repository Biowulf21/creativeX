<?php

namespace App\Http\Repositories\FollowRepository;

use App\Exceptions\AlreadyFollowingException;
use App\Exceptions\UserNotFollowingException;
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
            //FIXME: another user can make another user follow another user
            //without their permission
            //
            //TODO: implement checking if the user requesting the follow request is the same as the
            //one logged in

            $isFollowing = $this->isFollowing($followerUserId, $followingUserId);
            if ($isFollowing) throw new AlreadyFollowingException();

            $wasPreviouslyFollowing = Follow::withTrashed(true)
                ->where('follower_user_id', $followerUserId)
                ->where('following_user_id', $followingUserId)
                ->whereNotNull('deleted_at')
                ->first();


            if ($wasPreviouslyFollowing) {
                $wasPreviouslyFollowing->restore();

                $wasPreviouslyFollowing = Follow::with('following')->get()->first();

                return response()
                    ->json(['message' => 'followed successfully',
                            'follower'=> $followerUserId,
                            'now_following' =>$wasPreviouslyFollowing->following]);
            }


            //NOTE: In case of the user spamming the follow and unfollow
            //We can  implement rate limiting
            //read here: https://laravel.com/docs/10.x/rate-limiting#main-content

            $follow = Follow::create([
                'follower_user_id' => $followerUserId,
                'following_user_id' => $followingUserId]);

            $follow = Follow::with('following')->get()->first();


        return response()
            ->json(['message' => 'followed successfully',
                    'follower'=> $followerUserId,
                    'now_following' =>$follow->following],200);


        } catch (AlreadyFollowingException $e){
            throw $e;

        }
          catch (\Throwable $th) {
            return response()->json(['error' => $th], 500);
        }
    }

    public function unfollow(int $followerUserId, int $followingUserId)
    {
        $isFollowing = $this->isFollowing($followerUserId, $followingUserId);
        if (!$isFollowing) throw new UserNotFollowingException;

        Follow::where(['follower_user_id' => $followerUserId,
            'following_user_id' => $followingUserId])->delete();

        return response()->json(['message'=> 'Successfully unfollowed the user.'], 200);
    }

    public function getAllFollowers(int $userId)
    {
        $user = User::find($userId);

        if (!$user) throw new UserNotFoundException;


        // TODO: implement pagination

        // eager load the follower user data from the follower_user_id column in the follow table
        $usersThatFollow = Follow::where('following_user_id', $userId)
            ->with('follower')
            ->get();

        return response()->json(['message'=> 'Successfully received all users followed by this user.',
            'followers'=> $usersThatFollow ], 200);
    }

    public function getAllFollowing(int $userId)
    {
        $user = User::find($userId);
        if (!$user) throw new UserNotFoundException;

        return response()->json(['message'=> 'Successfully received all users followed
            by this user.', 'following'=> $user->following(), 200]);
    }
}
