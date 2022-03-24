<?php

namespace Modules\Dashboard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Dashboard\Events\BodyProgressSubmitted;
use Modules\Dashboard\Http\Requests\CreateProgressRequest;
use Modules\Dashboard\Http\Requests\DeleteProgressRequest;
use Modules\Dashboard\Http\Requests\UpdateProgressRequest;
use Modules\Dashboard\Services\DiaryService;
use Modules\Dashboard\Services\ProgressService;
use Throwable;

class ProgressController extends Controller
{

    /**
     * @return JsonResponse
     * @throws Throwable
     */
    public function canCreate(): JsonResponse
    {
        return response()->json([
            'canCreate' => (new ProgressService())->canWrite()
        ]);
    }

    /**
     * @throws Throwable
     */
    public function create(CreateProgressRequest $request, ProgressService $service): Response
    {
        $service->create($request->validated());
        event(new BodyProgressSubmitted());
        return response()->noContent();
    }

    /**
     * @throws Throwable
     */
    public function list(ProgressService $service): JsonResponse
    {
        return response()->json($service->list());
    }

    /**
     * @throws Throwable
     */
    public function update(UpdateProgressRequest $request, ProgressService $service): Response
    {
        $service->update($request->validated());
        return response()->noContent();
    }

    public function delete(DeleteProgressRequest $request, ProgressService $service): Response
    {
        $service->delete($request->validated()['id']);
        return response()->noContent();
    }

}
