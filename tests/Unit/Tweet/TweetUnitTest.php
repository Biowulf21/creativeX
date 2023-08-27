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
}
