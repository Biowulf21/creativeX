<?php

namespace Tests\Feature;

use App\Http\Repositories\TweetRepository\TweetRepository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FollowFeatureTest extends TestCase
{
    private User $user;
    private $repository;
    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory(1)->create()->first();
        $this->user = $user;
        $this->repository = app(TweetRepository::class);
    }


    private function createUserUsingPost()
    {

        $user = User::factory(1)->make();
        $user_array = $user->toArray()[0];
        $user_array['password'] = 'Password123!';

        $response = $this->actingAs($this->user)->post("/api/signup/", $user_array);
        $response->assertSessionHasNoErrors();
        print_r($response->json());
        $response->assertStatus(200);

        $this->assertDatabaseCount('users', 2);

        return $user;
    }

    public function test_successfully_follow_user()
    {
        $this->assertDatabaseCount('follows', 0);
        $this->assertDatabaseCount('users', 1);

        $user = $this->createUserUsingPost()[0];

        $following_id = $user->id;

        $this->assertDatabaseCount('users', 2);

        $url = '/user/' . $this->user->id . '/follow/'.$following_id;

        $response = $this->actingAs($this->user)->post($url);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
    }


}
