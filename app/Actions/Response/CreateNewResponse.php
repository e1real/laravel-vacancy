<?php declare(strict_types=1);

namespace App\Actions\Response;

use App\Contracts\ICreateNewContract;
use App\Models\Response;
use App\Models\User;
use App\Models\UserBalance;
use Illuminate\Database\Eloquent\Model;

class CreateNewResponse implements ICreateNewContract
{
    /**
     * Create new response handler
     *
     * @param array $validatedData
     * @param User $user
     * @return Model
     */
    public function handle(User $user, array $validatedData): Model
    {
        return Response::query()->create(array_merge($validatedData, ['sender_id' => $user->id]));
    }
}
