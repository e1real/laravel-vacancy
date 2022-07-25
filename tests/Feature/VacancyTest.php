<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Exceptions\NoLeftCoinsException;
use App\Models\User;
use App\Models\UserBalance;
use App\Models\Vacancy;
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
            'description' => $this->faker->paragraph,
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
        UserBalance::factory()->create(['user_id' => $user->id, 'coins' => 5]);

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
        UserBalance::factory()->create(['user_id' => $user->id, 'coins' => 5]);

        $attempt = env('USER_POST_VACANCIES_MAX_ATTEMPTS');

        $payload = $this->getVacancyCreateData();

        for ($i = 0; $i < $attempt; $i++) {
            $this->actingAs($user)->post('/api/vacancy', $payload);
        }

        $response = $this->actingAs($user)->post('/api/vacancy', $payload);

        // last check response getting 429 to many attempt
        $response->assertStatus(429);
    }


    public function test_to_post_a_job_vacancy_a_user_has_to_pay_two_coins() {
        $oldCoinsValue = 5;

        $user = User::factory()->create();
        $userBalance = UserBalance::factory()->create(['user_id' => $user->id, 'coins' => $oldCoinsValue]);
        $userBalance->keepForJobPosting();
        $userBalance->refresh();

        $this->assertTrue($userBalance->coins < $oldCoinsValue);
    }

    public function test_getting_cost_for_job_posting() {
        // env('VACANCY_POST_COST')
        $vacancyCost = 3;

        $user = User::factory()->create();
        $userBalance = UserBalance::factory()->create(['user_id' => $user->id, 'coins' => 1]);
        $this->expectException(NoLeftCoinsException::class);
        $userBalance->keepForJobPosting();

        $result = intval($userBalance->coins) - intval($vacancyCost);
        $this->assertLessThan(0, $result);
    }

}
