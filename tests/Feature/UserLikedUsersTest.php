<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserLikedUsersTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_like_another_user()
    {
        $user = User::factory()->create();

        $beautyUser = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(
                '/api/user-liked-users/',
                [
                    'liked_user_id' => $beautyUser->id,
                ],
                [
                    'accept' => 'application/json',
                ]
            );

        $response->assertStatus(201);

        $this->assertDatabaseHas('user_liked_users', [
            'liked_user_id' => $beautyUser->id,
            'user_id' => $user->id,
        ]);
    }
}
