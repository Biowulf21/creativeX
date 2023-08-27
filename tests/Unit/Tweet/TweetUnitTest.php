<?php

namespace Tests\Unit;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
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

    public function test_unsuccessfully_create_tweet_user_doesnt_exist()
    {
        $this->assertDatabaseCount('tweets', 0);
        $this->assertDatabaseCount('users', 1);

        $current_user_id = $this->user->id;
        $user_not_in_db_id = $current_user_id + 1;

        // Loop until it finds an id that doesn't exist in the users database
        while (User::where('id', $user_not_in_db_id)->exists()) {
            $user_not_in_db_id++;
        }

        $tweet = Tweet::factory()->make(['user_id' => $user_not_in_db_id]);

        $response = $this->actingAs($this->user)->post("/api/tweets/", $tweet->toArray());
        $response->assertStatus(400);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'user_id'
            ],
        ]);
    }

    public function test_unsuccessfully_create_tweet_no_attachment_when_retweeting()
    {
        $tweet = Tweet::factory()->make([
            'is_retweet' => true,
            'tweet_attachment' => ['fake_image.jpg'], // Adding a dummy file just to trigger the array validation
        ]);
        $response = $this->actingAs($this->user)->post("/api/tweets/", $tweet->toArray());
        $response->assertStatus(400);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'tweet_attachment'
            ],
        ]);
    }


    public function test_unsuccessfully_create_tweet_multiple_attachment_upload()
    {
        $tweet = Tweet::factory()->make([
            'is_retweet' => true,
            'tweet_attachment' => ['fake_image.jpg', 'fake_image2.jpg'], // Adding a dummy file just to trigger the array validation
        ]);
        $response = $this->actingAs($this->user)->post("/api/tweets/", $tweet->toArray());
        $response->assertStatus(400);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'tweet_attachment'
            ],
        ]);
    }

    public function test_unsuccessfully_create_tweet_attachment_too_large()
    {
        // Larger than 2MB
        $fakeImage = Image::canvas(3000, 3000);

        $tweet = Tweet::factory()->make([
            'is_retweet' => false,
            'tweet_attachment' => [$fakeImage],
        ]);

        $response = $this->actingAs($this->user)->post("/api/tweets/", $tweet->toArray());
        $response->assertStatus(400);

        Storage::disk('public')->delete($fakeImage);
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



    public function test_unsuccessfully_get_tweet_doenst_exist()
    {
        $tweet = $this->createTweetUsingPost();
        $tweet_id = $tweet->id;

        while (Tweet::where('id', $tweet_id)->exists()) {
            $tweet_id++;
        }

        $response = $this->actingAs($this->user)->get("/api/tweets/$tweet_id");
        $response->assertStatus(404);

        $response->assertJson([
            'message' => 'Tweet not found'
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
