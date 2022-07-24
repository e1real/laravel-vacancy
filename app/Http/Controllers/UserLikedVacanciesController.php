<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserLikedVacanciesRequest;
use App\Http\Requests\UpdateUserLikedVacanciesRequest;
use App\Models\UserLikedVacancies;

class UserLikedVacanciesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserLikedVacanciesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function like(StoreUserLikedVacanciesRequest $request)
    {
        $validatedData = $request->validated();

        UserLikedVacancies::query()->create(array_merge(
            ['user_id' => $request->user()->id],
            $validatedData
        ));
    }
}
