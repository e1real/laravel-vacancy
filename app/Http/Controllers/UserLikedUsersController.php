<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserLikedUsersRequest;
use App\Models\UserLikedUsers;
use Illuminate\Http\JsonResponse;

class UserLikedUsersController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreUserLikedUsersRequest  $request
     * @return JsonResponse
     */
    public function store(StoreUserLikedUsersRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $model = UserLikedUsers::query()->create(array_merge(
            ['user_id' => $request->user()->id],
            $validatedData
        ));

        return response()->json($model, 201);
    }
}
