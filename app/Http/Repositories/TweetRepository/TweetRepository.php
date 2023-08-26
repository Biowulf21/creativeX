<?php

namespace App\Http\Repositories\TweetRepository;

use App\Http\Repositories\TweetRepository\TweetRepositoryInterface;
use App\Models\Tweet;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class TweetRepository implements TweetRepositoryInterface
{
    public function createTweet(array $data)
    {
        $user_id = Auth::user()->id;

        $retweet_count = 0;
        $likes_count = 0;
        $is_retweet = $data['is_retweet'] == '1' ? true : false;

        $data['user_id'] = $user_id; // Set the user_id

        $data['retweet_count'] = $retweet_count;
        $data['likes_count'] = $likes_count;
        $data['is_retweet'] = $is_retweet;

        // TODO: Check if the tweet is a retweet and use retweet controller instead

        $new_tweet = new Tweet;
        $new_tweet->fill($data);
        $new_tweet->user_id = $user_id;
        $new_tweet->save();

        return response()->json(['message' => 'Tweet created successfully', 'tweet_body'=>$new_tweet], 200);

    }

    public function getTweet(int $id)
    {
    }

    public function getTweets()
    {
    }

    public function deleteTweet(int $id)
    {
        try {

            $tweet_to_delete = Tweet::findOrFail($id);
            $tweet_id = $tweet_to_delete->id;
            $tweet_to_delete->delete();

            return response()->json(['message'=>'Sucessfully deleted tweet with ID #' . $tweet_id, 'deleted_tweet_id'=> $tweet_id], 200);


        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Cannot delete. Tweet not found.'], 404);
        }
    }

}
