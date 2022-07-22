<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vacancy;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\RateLimiter;
use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Exception;
use Tests\TestCase;

class VacancyTest extends TestCase
{

    /**
     * Get vacancy request payload
     * @return array
     */
    public function getVacancyCreateData(): array
    {
        return [
            'title' => $this->faker->name,
            'description' => $this->faker->paragraph
        ];
    }

    /**
     * Any user can create new job vacancy
     *
     * @return void
     */
    public function test_any_user_can_post_new_job_vacancy()
    {
        $user = User::factory()->create();
        $payload = $this->getVacancyCreateData();
        $response = $this->actingAs($user)->post('/api/vacancy', $payload);

        $response->assertStatus(201);
        $jsonResponse = $response->json();

        $this->assertDatabaseHas('vacancies', [
            'title' => $payload['title'],
            'description' => $payload['description'],
            'owner_id' => $jsonResponse['owner_id'],
        ]);
    }

    public function test_user_cannot_post_more_than_two_job_vacancies_per_24_hours()
    {
        $user = User::factory()->create();
        $attempt = env('USER_POST_VACANCIES_MAX_ATTEMPTS');

        $payload = $this->getVacancyCreateData();

        for ($i = 0; $i < $attempt; $i++) {
            $this->actingAs($user)->post('/api/vacancy', $payload);
        }

        $response = $this->actingAs($user)->post('/api/vacancy', $payload);

        // last check response getting 429 to many attempt
        $response->assertStatus(429);
    }

}
