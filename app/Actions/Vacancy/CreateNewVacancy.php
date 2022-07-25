<?php declare(strict_types=1);

namespace App\Actions\Vacancy;

use App\Contracts\ICreateNewContract;
use App\Exceptions\NoLeftCoinsException;
use App\Exceptions\ToManyVacancyPerUser;
use App\Models\User;
use App\Models\UserBalance;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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

            

            try {
                DB::beginTransaction();
                // try to get coins after create vacancy
                $userBalance = UserBalance::query()->where('user_id', $user->id)->first();
                $userBalance->keepForJobPosting();

                $model = Vacancy::query()->create(array_merge($validatedData, ['owner_id' => $user->id]));

                RateLimiter::hit('create-new-vacancy:'.$user->id, $limitPerSecond);

                DB::commit();

                return $model;
            } catch (\Exception $exception) {
                logger($exception->getTraceAsString());
                DB::rollBack();
            }
        }

        throw new ToManyVacancyPerUser;
    }
}
