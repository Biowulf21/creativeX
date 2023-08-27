<?php

namespace Tests\Unit;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Repositories\TweetRepository\TweetRepository;

class TweetUnitTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private User $user;
    private $repository;
    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory(1)->create()->first();
        $this->user = $user;
        $this->repository = app(TweetRepository::class);
    }

    public function test_successfully_create_tweet()
    {
        $this->assertDatabaseCount('tweets', 0);

        $tweet = Tweet::factory(1)->make();
        $tweet = $tweet->toArray();

        $response = $this->actingAs($this->user)->post("/api/tweets/", $tweet[0]);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message',
            'tweet' => [
                'tweet_body',
                'is_retweet',
                'user_id',
                'tweet_attachment_link',
                'updated_at',
                'replying_to',
                'created_at',
                'id'
            ],
        ]);

        $this->assertDatabaseCount('tweets', 1);
    }

    private function createTweetUsingPost()
    {

        $this->assertDatabaseCount('tweets', 0);
        $tweet = Tweet::factory(1)->make();
        $tweet = $tweet->toArray();

        $response = $this->actingAs($this->user)->post("/api/tweets/", $tweet[0]);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);

        $this->assertDatabaseCount('tweets', 1);

        return Tweet::first();
    }


    public function test_successfully_get_specific_tweet()
    {
        $tweet = $this->createTweetUsingPost();
        $tweet_id = $tweet->id;


        $response = $this->actingAs($this->user)->get("/api/tweets/$tweet_id");
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message',
            'tweet' => [
                'id',
                'tweet_body',
                'replying_to',
                'user_id',
                'likes_count',
                'retweets_count',
                'is_retweet',
                'tweet_attachment_link',
                'deleted_at',
                'created_at',
                'updated_at',
            ],
        ]);

        $response->assertJson([
        'tweet' => [
            'id' => $tweet_id,
            ],
        ]);
    }

    public function test_update_tweet()
    {
        $tweet = $this->createTweetUsingPost();
        $old_tweet_body = $tweet->tweet_body;
        $tweet_id = $tweet->id;

        $new_tweet_body = 'This is the new tweet body';
        $patch_payload = ['new_tweet_body' => $new_tweet_body ];
        $response = $this->actingAs($this->user)->patch("/api/tweets/$tweet_id", $patch_payload);
        $updated_tweet_body = $response->json()['new_tweet_body'];

        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $this->assertEquals($new_tweet_body, $updated_tweet_body);
    }

    public function test_successfully_get_delete_tweet()
    {
        $this->assertDatabaseCount('tweets', 0);
        $tweet = $this->createTweetUsingPost();
        $this->assertDatabaseCount('tweets', 1);

        $tweet_id = $tweet->id;
        $response = $this->actingAs($this->user)->delete("/api/tweets/$tweet_id");

        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);

        // Check that the tweet has been soft deleted
        $tweet = Tweet::withTrashed()->find($tweet_id);
        $this->assertNotNull($tweet->deleted_at);
        $this->assertDatabaseCount('tweets', 1);
    }



}
