<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Vacancy\CreateNewVacancy;
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
        $model = $action->handle($request->user(), $request->validated());

        return response()->json($model, 201);
    }

}
