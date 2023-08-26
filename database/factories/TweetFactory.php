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
        return [
        'tweet_body' => fake()->realText(280),
        'replying_to' => fake()->randomElement([Tweet::inRandomOrder()->value('id'), null]),
        'user_id' => function () { return User::factory()->make()->id; },
        'likes_count' => fake()->numberBetween(0, 100),
        'retweets_count' => fake()->numberBetween(0, 100),
        'is_retweet' => fake()->boolean,
        'tweet_attachment_link' => fake()->imageUrl(), // Generate a sample image URL
                ];
    }
}
