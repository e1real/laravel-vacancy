<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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


    }
}
