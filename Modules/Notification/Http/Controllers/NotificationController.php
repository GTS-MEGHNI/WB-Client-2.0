<?php

namespace Modules\Notification\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Notification\Http\Requests\CheckNotificationRequest;
use Modules\Notification\Http\Requests\NotificationListRequest;
use Modules\Notification\Services\NotificationService;

class NotificationController extends Controller
{
    public function list(NotificationListRequest $request, NotificationService $service) : JsonResponse{
        return response()->json($service->list($request->validated()));
    }

    public function check(NotificationService $service): Response
    {
        $service->check();
        return response()->noContent();
    }

    public function checkAll(NotificationService $service): Response
    {
        $service->checkAll();
        return response()->noContent();
    }
}
