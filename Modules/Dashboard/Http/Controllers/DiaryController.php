<?php

namespace Modules\Dashboard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Dashboard\Http\Requests\CreateDiaryRequest;
use Modules\Dashboard\Http\Requests\DeleteDiaryRequest;
use Modules\Dashboard\Http\Requests\DiaryListRequest;
use Modules\Dashboard\Http\Requests\UpdateDiaryRequest;
use Modules\Dashboard\Services\DiaryService;
use Throwable;

class DiaryController extends Controller
{
    /**
     * @return JsonResponse
     * @throws Throwable
     */
    public function canCreate(): JsonResponse
    {
        return response()->json([
            'canCreate' => true//(new DiaryService())->canWrite()
        ]);
    }

    /**
     * @throws Throwable
     */
    public function create(CreateDiaryRequest $request, DiaryService $service): Response
    {
        $service->create($request->validated());
        return response()->noContent();
    }

    /**
     * @throws Throwable
     */
    public function list(DiaryListRequest $request, DiaryService $service): JsonResponse
    {
        return response()->json($service->list($request->validated()));
    }

    public function update(UpdateDiaryRequest $request, DiaryService $service): Response
    {
        $service->update($request->validated());
        return response()->noContent();
    }

    public function delete(DeleteDiaryRequest $request, DiaryService $service): Response
    {
        $service->delete($request->validated()['id']);
        return response()->noContent();
    }

    /**
     * @param DiaryService $service
     * @return JsonResponse
     * @throws Throwable
     */
    public function progress(DiaryService $service): JsonResponse {
        return response()->json($service->progress());
    }
}
