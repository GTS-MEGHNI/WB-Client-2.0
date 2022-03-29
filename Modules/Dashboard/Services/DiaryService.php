<?php

namespace Modules\Dashboard\Services;

use App\Utility;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Modules\Dashboard\Entities\DiaryActivityModel;
use Modules\Dashboard\Entities\DiaryFeelingModel;
use Modules\Dashboard\Entities\DiaryModel;
use Modules\Subscription\Entities\SubscriptionModel;
use Modules\Subscription\Traits\Order;
use Throwable;

class DiaryService
{
    use Order;

    /**
     * @throws Throwable
     */
    public function create(array $payload)
    {
        $row = DiaryModel::forceCreate([
            'order_id' => request()->get('order')->id,
            'sleep' => $payload['sleep'],
            'mood' => $payload['mood'],
            'energy' => $payload['energy'],
            'title' => $payload['title'],
            'note' => Arr::exists($payload, 'note') ? $payload['note'] : null
        ]);
        $this->insertFeelings($payload, $row);
        $this->insertActivities($payload, $row);
    }

    /**
     * @throws Throwable
     * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
     */
    public function list(array $payload): array
    {
        $page = $payload['page'];
        $length = $payload['length'];
        dd(Utility::getUserOngoingSubscription()->diaries);
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        $reach_end = !$diaries->paginate($length)->hasMorePages();
        $diaries->paginate($length);
        if ($diaries->count() == 0)
            $reach_end = true;
        return [
            'reachEnd' => $reach_end,
            'list' => $diaries->get()->toArray()
        ];
    }

    public function update(array $payload)
    {
        $row = DiaryModel::find(request()->route('diaryId'));
        $row->sleep = $payload['sleep'];
        $row->mood = $payload['mood'];
        $row->energy = $payload['energy'];
        $row->title = $payload['title'];
        $row->note = Arr::exists($payload, 'note') ? $payload['note'] : null;
        $row->save();
        $this->deleteActivities($row);
        $this->deleteFeelings($row);
        $this->insertActivities($payload, $row);
        $this->insertFeelings($payload, $row);
    }

    private function deleteFeelings($row)
    {
        $row->feelings()->delete();
    }

    private function deleteActivities($row)
    {
        $row->activities()->delete();
    }

    private function insertFeelings($payload, $row)
    {
        foreach ($payload['feelings'] as $feeling)
            DiaryFeelingModel::forceCreate([
                'diary_id' => $row->id,
                'feeling' => $feeling
            ]);
    }

    private function insertActivities($payload, $row)
    {
        foreach ($payload['activities'] as $activity)
            DiaryActivityModel::forceCreate([
                'diary_id' => $row->id,
                'activity' => $activity
            ]);
    }

    public function delete($id)
    {
        $row = DiaryModel::find(request()->route('diaryId'));
        $this->deleteActivities($row);
        $this->deleteFeelings($row);
        $row->delete();
    }

    /**
     * @return bool
     * @throws Throwable
     */
    public function canWrite(): bool
    {
        $order = $this->getUserLatestOrder();
        $latest_diary = $order->diaries()?->latest()?->first();
        if ($latest_diary == null)
            return true;
        $tomorrow = Carbon::parse($latest_diary->created_at)->tomorrow();
        return Carbon::now()->greaterThanOrEqualTo($tomorrow);
    }

    /**
     * @return mixed
     * @throws Throwable
     */
    public function progress(): array
    {
        return request()->get('order')->diaries->toArray();
    }

}
