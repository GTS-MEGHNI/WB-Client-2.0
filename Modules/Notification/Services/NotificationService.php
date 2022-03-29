<?php

namespace Modules\Notification\Services;

use App\Utility;
use Illuminate\Pagination\Paginator;
use Modules\Notification\Entities\NotificationModel;

class NotificationService
{

    public function list(array $payload): array
    {
        $page = $payload['page'];
        $page_size = $payload['length'];
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        $notifications = NotificationModel::latest()->where([
            'user_id' => Utility::getUserId()
        ]);
        $new = NotificationModel::where([
            'user_id' => Utility::getUserId(),
            'new' => 1
        ])->count();
        $reach_end = !$notifications->paginate($page_size)->hasMorePages();
        $notifications->paginate($page_size);
        return Utility::remove_array_shape_tag([
            'reachEnd' => $reach_end,
            'new' => $new,
            'list' => $notifications->get()->toArray()
        ]);
    }

    public function check(): void
    {
        NotificationModel::unguard();
        NotificationModel::where([
            'user_id' => Utility::getUserId(),
            'id' => request()->route('notificationId')
        ])->update(['new' => 0]);
        NotificationModel::reguard();
    }

    public function checkAll() : void {
        NotificationModel::unguard();
        NotificationModel::where([
            'user_id' => Utility::getUserId()
        ])->update(['new' => 0]);
        NotificationModel::reguard();
    }

}
