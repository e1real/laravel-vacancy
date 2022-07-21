<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class VacancyTest extends TestCase
{
    /**
     * Any user can create new job vacancy
     *
     * @return void
     */
    public function test_any_user_can_post_new_job_vacancy()
    {
        $anyUser = User::factory()->create();

        $data = [
            'title' => $this->faker->name,
            'description' => $this->faker->paragraph
        ];

        $response = $this->actingAs($anyUser)->post('/api/vacancy', $data);

        $response->assertStatus(201);

        $jsonResponse = $response->json();

        $this->assertDatabaseHas('vacancies', [
            'title' => $data['title'],
            'description' => $data['description'],
            'owner_id' => $jsonResponse['owner_id'],
        ]);
    }

}
