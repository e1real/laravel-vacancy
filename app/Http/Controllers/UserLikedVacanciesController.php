<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserLikedVacanciesRequest;
use App\Models\UserLikedVacancies;
use Illuminate\Http\JsonResponse;

class UserLikedVacanciesController extends Controller
{
    public function store(StoreUserLikedVacanciesRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $model = UserLikedVacancies::query()->create(array_merge(
            ['user_id' => $request->user()->id],
            $validatedData
        ));

        return response()->json($model, 201);
    }
}
