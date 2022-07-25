<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Vacancy\CreateNewVacancy;
use App\Exceptions\ToManyVacancyPerUser;
use App\Exceptions\NoLeftCoinsException;
use App\Http\Requests\StoreVacancyRequest;
use Illuminate\Http\JsonResponse;

class VacancyController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreVacancyRequest $request
     * @param CreateNewVacancy $action
     * @return JsonResponse
     */
    public function store(StoreVacancyRequest $request, CreateNewVacancy $action): JsonResponse
    {
        try {
            $model = $action->handle($request->user(), $request->validated());
        } catch (ToManyVacancyPerUser $exception) {
            return response()->json(['message' => 'To many request'], 429);
        } catch (NoLeftCoinsException $exception) {
            return response()->json(['message' => ' You are out of coins'], 422);
        }

        return response()->json($model, 201);
    }
}
