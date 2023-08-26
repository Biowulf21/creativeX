<?php

namespace Database\Factories;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tweet>
 */
class TweetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $tweetIDorNull = fake()->randomElement([function () {
            $tweet = Tweet::inRandomOrder()->first();
            return $tweet ? $tweet->id : null;
        }, null]);

        $userID = User::inRandomOrder()->first()->id;

        return [
        'tweet_body' => fake()->realText(280),

        //NOTE:: The following line is a bit more complex than the others.
        //It uses the fake() helper function to generate a random element from an array of values.
        //The array contains two values: a function that returns a random tweet ID,
        //and null. This is to ensure that the tweet can be a reply to another tweet,

        'replying_to' => $tweetIDorNull,
        'user_id' => $userID,
        'likes_count' => fake()->numberBetween(0, 100),
        'retweets_count' => fake()->numberBetween(0, 100),
        'is_retweet' => fake()->boolean,
        'tweet_attachment_link' => fake()->imageUrl(), // Generate a sample image URL
                ];
    }
}
