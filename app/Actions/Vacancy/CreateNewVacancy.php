<?php declare(strict_types=1);

namespace App\Actions\Vacancy;

use App\Contracts\ICreateNewContract;
use App\Exceptions\ToManyVacancyPerUser;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\RateLimiter;

class CreateNewVacancy implements ICreateNewContract
{

    /**
     * Create new vacancy handler
     *
     * @param array $validatedData
     * @param User $user
     * @return Model
     * @throws ToManyVacancyPerUser
     */
    public function handle(User $user, array $validatedData): Model
    {
        $attempts = env('USER_POST_VACANCIES_MAX_ATTEMPTS');
        $limitPerSecond = env('USER_POST_VACANCIES_LIMIT_PER_SECOND');

        if (RateLimiter::remaining('create-new-vacancy:'.$user->id, $attempts)) {

            $model = Vacancy::query()->create(array_merge($validatedData, ['owner_id' => $user->id]));
            RateLimiter::hit('create-new-vacancy:'.$user->id, $limitPerSecond);

            return $model;
        }

        throw new ToManyVacancyPerUser;
    }
}
