<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Response\CreateNewResponse;
use App\Exceptions\ToManyVacancyPerUser;
use App\Http\Requests\StoreResponseRequest;
use Illuminate\Http\JsonResponse;

class ResponseController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreResponseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreResponseRequest $request, CreateNewResponse $action): JsonResponse
    {
        $model = $action->handle($request->user(), $request->validated());

        return response()->json($model, 201);
    }
}
