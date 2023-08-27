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


        /* $tweet = Tweet::factory(1)->make(); */
        /* $tweet = $tweet->toArray(); */
        /**/
        /* $response = $this->actingAs($this->user)->post("/api/tweet", $tweet[0]); */
        /**/
        /* $response->assertSessionHasNoErrors(); */
        /* $response->assertStatus(200); */
        /* $this->assertDatabaseHas('tweets', [ */
        /*     'user_account_handle'=> $this->user->account_handle, */
        /*     'text' => $tweet[0]['text'] */
        /* ]); */
        /* $this->assertDatabaseCount('tweets', 1); */
    }
}
