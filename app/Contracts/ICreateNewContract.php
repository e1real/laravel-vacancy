<?php


namespace App\Contracts;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface ICreateNewContract
{
    /**
     * Create new vacancy handler
     *
     * @param  array  $validatedData
     * @param User $user
     * @return Model
     */
    public function handle(User $user, array $validatedData): Model;
}
