<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vacancy;
use Tests\TestCase;

class ResponseTest extends TestCase
{
    /**
     * any_user_can_send_a_response_to_job_vacancies_posted_by_other_users
     *
     * @return void
     */
    public function test_any_user_response_to_job_vacancies_posted_by_other_users()
    {
        $vacancyCreator = User::factory()->create();

        $vacancy = Vacancy::factory()->create(['owner_id' => $vacancyCreator]);

        // request user
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(
            '/api/response/',
            [
                'message' => 'Hello mym name is Esbol! I\'m fullstack developer',
                'vacancy_id' => $vacancy->id,
            ]
        );
        $response->assertStatus(201);

        $responseArray = $response->json();
        unset($responseArray['created_at']);
        unset($responseArray['updated_at']);

        $this->assertEquals(
            $responseArray,
            [
                'message' => 'Hello mym name is Esbol! I\'m fullstack developer',
                'vacancy_id' => 1,
                'sender_id' => 2,
                'id' => 1,
            ]
        );
    }
}
