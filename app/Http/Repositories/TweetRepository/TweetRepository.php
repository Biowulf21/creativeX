<?php

namespace App\Http\Repositories\TweetRepository;

use App\Http\Repositories\Attachment\AttachmentRepositoryInterface;
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
        $tweet_attachment = $data['tweet_attachment'] ??= null;

        $data['user_id'] = $user_id; // Set the user_id

        $data['retweet_count'] = $retweet_count;
        $data['likes_count'] = $likes_count;
        $data['is_retweet'] = $is_retweet;

        // TODO: Check if the tweet is a retweet and use retweet controller instead

        $new_tweet = new Tweet;
        $new_tweet->fill($data);
        $new_tweet->user_id = $user_id;
        $new_tweet->tweet_attachment_link = $tweet_attachment;

        if ($tweet_attachment != null){
            $attachmentRepository = app(AttachmentRepositoryInterface::class);
            $upload_response = $attachmentRepository->uploadAttachment($tweet_attachment);
            if($upload_response->status() == 500) return $upload_response;
        }

        echo $new_tweet->tweet_attachment;
        $new_tweet->save();

        return response()->json(['message' => 'Tweet created successfully', 'tweet_body'=>$new_tweet], 200);

    }


    public function updateTweet(int $id, array $data)
    {
        try {

            $tweet = Tweet::findOrFail($id);
            $tweet->tweet_body = $data['new_tweet_body'];

            $tweet->save();
            return response()->json(['message' => 'Tweet updated successfully', 'new_tweet_body'=>$tweet['tweet_body'], 'updated_tweet'=>$tweet], 200);

        } catch (ModelNotFoundException) {

            return response()->json(['message' => 'Cannot update. Tweet not found.'], 404);

        }

    }



    public function getTweet(int $id)
    {
        try{

            $tweet = Tweet::findOrFail($id);
            return response ()->json(['message' => 'Tweet retrieved successfully', 'tweet_body'=>$tweet], 200);
        } catch (ModelNotFoundException){
            return response()->json(['message' => 'Tweet not found'], 404);
        }


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
