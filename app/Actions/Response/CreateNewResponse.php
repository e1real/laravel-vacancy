<?php declare(strict_types=1);

namespace App\Actions\Response;

use App\Contracts\ICreateNewContract;
use App\Exceptions\ToManyVacancyPerUser;
use App\Models\Response;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CreateNewResponse implements ICreateNewContract
{
    /**
     * Create new response handler
     *
     * @param array $validatedData
     * @param User $user
     * @return Model
     * @throws ToManyVacancyPerUser
     */
    public function handle(User $user, array $validatedData): Model
    {
        $model = Response::query()->create(array_merge($validatedData, ['sender_id' => $user->id]));

        return $model;
    }
}
