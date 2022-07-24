<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vacancy;
use Tests\TestCase;

class UserLikedVacanciesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_like_a_job_vacancy()
    {
        $user = User::factory()->create();

        $vacancyCreator = User::factory()->create();
        $vacancy = Vacancy::factory()->create(['owner_id' => $vacancyCreator->id]);

        $response = $this->actingAs($user)
            ->post(
                '/api/user-liked-vacancies/',
                [
                    'vacancy_id' => $vacancy->id,
                ],
                [
                    'accept' => 'application/json',
                ]
            );

        $response->assertStatus(201);

        $this->assertDatabaseHas('user_liked_vacancies', [
            'vacancy_id' => $vacancy->id,
            'user_id' => $user->id,
        ]);
    }
}
