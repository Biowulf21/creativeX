<?php

namespace Tests\Unit;

use App\Http\Repositories\UserRepository\UserRepository as AppUserRepository;
use App\Models\User;
use Database\Factories\UserFactory;
use Tests\TestCase;

use App\Exceptions\PasswordMismatchException;
use App\Http\Repositories\User\UserRepository;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;

class UserUnitTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private $repository;
    public function setUp(): void
    {
        parent::setUp();
        $this->repository = app(AppUserRepository::class);
    }

    public function test_successfully_sign_up_user()
    {
        $this->assertDatabaseCount('users', 0);
        $user = User::factory ()->create();
        $user = [
            'name' => 'Giles Lang',
            'password' => 'Password123!',
            'email' => 'forrest83@example.net',
            'account_handle' => 'deron.yundt',
            'bio' => 'Consequuntur commodi assumenda ut ex consequatur ad. Soluta et aut quae facilis. Magni fugiat fuga ut veniam libero.',
        ];

        $response = $this->post('/api/signup', $user);

        $this->assertEquals(200, $response->getStatusCode());

        // Assert specific attributes within the 'user' object
        $response->assertJsonStructure([
            'user' => [
                'name',
                'email',
                'account_handle',
                'bio',
                'updated_at',
                'created_at',
                'id'
            ],
            'token'
        ]);

    }

    public function test_unsucessfully_sign_up_user_missing_password()
    {
        $this->assertDatabaseCount('users', 0);
        $user = User::factory ()->create();
        $user = [
            'name' => 'Giles Lang',
            'email' => 'forrest83@example.net',
            'account_handle' => 'deron.yundt',
            'bio' => 'Consequuntur commodi assumenda ut ex
            consequatur ad. Soluta et aut quae facilis. Magni fugiat f uga ut veniam libero.',
        ];


        $response = $this->post('/api/signup', $user);

        $this->assertEquals(400, $response->getStatusCode());


        $response->assertJsonStructure([
            'message',
            'errors' => [
                'password'
            ]
        ]);
        }

 public function test_unsuccessfully_sign_up_user_already_exists()
    {
        $this->assertDatabaseCount('users', 0);

        // Create a user using the factory or other appropriate method
        $existingUser = User::factory()->create();

        // Define user data
        $userData = [
            'name' => 'Giles Lang',
            'password' => 'Password123!',
            'email' => 'forrest83@example.net',
            'account_handle' => 'deron.yundt',
            'bio' => 'Consequuntur commodi assumenda ut ex consequatur ad. Soluta et aut quae facilis. Magni fugiat fuga ut veniam libero.',
        ];

        // First sign-up attempt (should succeed)
        $firstResponse = $this->post('/api/signup', $userData);
        $firstResponse->assertStatus(200);

        // Second sign-up attempt with the same user data (should fail)
        $secondResponse = $this->post('/api/signup', $userData);
        $secondResponse->assertStatus(400);

        $secondResponse->assertJsonStructure([
            'message',
            'errors' => [
                'email',
                'account_handle'
            ]
        ]);
    }
}
