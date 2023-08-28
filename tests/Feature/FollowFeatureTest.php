<?php

namespace Tests\Feature;

use App\Http\Repositories\TweetRepository\TweetRepository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FollowFeatureTest extends TestCase
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


    private function createUserUsingPost()
    {

        $user = User::factory(1)->make();
        $user_array = $user->toArray()[0];
        $user_array['password'] = 'Password123!';


        $response = $this->actingAs($this->user)->post("/api/signup/", $user_array);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $this->assertDatabaseCount('users', 2);

        $user_data = $response->json()['user'];

        return $user_data;
    }

    public function test_successfully_follow_user()
    {
        $this->assertDatabaseCount('follows', 0);
        $this->assertDatabaseCount('users', 1);

        $user = $this->createUserUsingPost();

        $following_id = $user['id'];

        $this->assertDatabaseCount('users', 2);

        $url = 'api/user/' . $this->user->id . '/follow/'.$following_id;

        $response = $this->actingAs($this->user)->post($url);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $this->assertDatabaseHas('follows', [
                'follower_user_id' => $this->user->id,
                'following_user_id' => $following_id,
            ]);
    }


    public function test_unsuccessfully_follow_user_user_to_be_followed_doesnt_exist()
    {
        $this->assertDatabaseCount('follows', 0);
        $this->assertDatabaseCount('users', 1);

        $nonExistentUserId = 999; // An ID that doesn't exist in the users table

        $url = 'api/user/' . $this->user->id . '/follow/' . $nonExistentUserId;

        $response = $this->actingAs($this->user)->post($url);

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Cannot follow a user that doesn\'t exist.',
        ]);

        // Assert that no relationship has been created in the database
        $this->assertDatabaseCount('follows', 0);

    }


}
