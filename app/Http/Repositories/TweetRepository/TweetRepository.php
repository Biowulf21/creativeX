<?php

namespace App\Http\Repositories\TweetRepository;

use App\Http\Repositories\Attachment\AttachmentRepositoryInterface;
use App\Http\Repositories\TweetRepository\TweetRepositoryInterface;
use App\Models\Tweet;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class TweetRepository implements TweetRepositoryInterface
{
    public function createTweet(array $data)
    {
        $new_tweet = $this->populateNewTweetAttributes($data);
        $new_tweet_attachment = $new_tweet->tweet_attachment_link;

        if ($new_tweet_attachment != null){
            $attachmentRepository = app(AttachmentRepositoryInterface::class);
            $upload_response = $attachmentRepository->uploadAttachment($new_tweet_attachment);

            if($upload_response->status() == 500) return $upload_response;
            $new_tweet->tweet_attachment_link = $upload_response->getData()->file_path;
        }

        $new_tweet->save();

        $new_tweet = $this->findReplyingTo($new_tweet, $new_tweet->replying_to);

        return response()->json(['message' => 'Tweet created successfully', 'tweet'=>$new_tweet], 200);

    }

    private function findReplyingTo(Tweet $tweet, int|null $replying_to_id)
    {

        if ($replying_to_id != null){
            $tweet_replied_to = Tweet::find($tweet->replying_to);
            $tweet->replying_to = $tweet_replied_to;
        } else {
            $tweet->replying_to = null;
        }
        return $tweet;


    }

    private function populateNewTweetAttributes(array $data)
    {

        $user_id = Auth::user()->id;

        $retweet_count = 0;
        $likes_count = 0;
        $is_retweet = $data['is_retweet'] == '1' ? true : false;
        $tweet_attachment = $data['tweet_attachment'][0] ??= null;

        $data['user_id'] = $user_id;

        $replying_to = $data['replying_to'] ?? null;

        $data['retweet_count'] = $retweet_count;
        $data['likes_count'] = $likes_count;
        $data['is_retweet'] = $is_retweet;

        $new_tweet = new Tweet;
        $new_tweet->fill($data);
        $new_tweet->user_id = $user_id;
        $new_tweet->tweet_attachment_link = $tweet_attachment;
        $new_tweet = $this->findReplyingTo($new_tweet, $replying_to);
        return $new_tweet;

    }

    public function getUserTweets(int $user_id)
    {
        $tweets = Tweet::where('user_id', $user_id)->get();
        return response()->json(['message' => 'Tweets retrieved successfully', 'tweets'=>$tweets], 200);

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
            $tweet = $this->findReplyingTo($tweet, $tweet->replying_to);
            return response ()->json(['message' => 'Tweet retrieved successfully', 'tweet'=>$tweet], 200);
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
