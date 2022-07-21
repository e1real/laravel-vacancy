<?php declare(strict_types=1);

namespace App\Actions\Vacancy;

use App\Contracts\ICreateNewContract;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Model;

class CreateNewVacancy implements ICreateNewContract
{

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $validatedData
     * @param User $user
     * @return Model
     */
    public function handle(User $user, array $validatedData): Model
    {
        return Vacancy::query()->create(array_merge($validatedData, ['owner_id' => $user->id]));
    }
}
