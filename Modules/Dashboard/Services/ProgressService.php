<?php

namespace Modules\Dashboard\Services;

use App\Utility;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Dashboard\Entities\BodyProgressModel;
use Modules\Dashboard\Entities\ProgressTracePhotoModel;
use Modules\Subscription\Traits\Order;
use Throwable;

class ProgressService
{
    use Order;

    /**
     * @return array
     * @throws Throwable
     */
    public function list(): array
    {
        return request()->get('order')->bodyProgresses->toArray();
    }

    /**
     * @param array $payload
     * @throws Throwable
     */
    public function update(array $payload)
    {
        $row = BodyProgressModel::find($payload['id']);
        $row->weight = $payload['weight'];
        $row->heart_rate = $payload['restingHeartRate'];
        $row->neck = $payload['bodyParts']['neck'];
        $row->shoulders = $payload['bodyParts']['shoulders'];
        $row->bust = $payload['bodyParts']['bust'];
        $row->chest = $payload['bodyParts']['chest'];
        $row->waist = $payload['bodyParts']['waist'];
        $row->abs = $payload['bodyParts']['abs'];
        $row->hips = $payload['bodyParts']['hips'];
        $row->left_bicep = $payload['bodyParts']['leftBicep'];
        $row->right_bicep = $payload['bodyParts']['rightBicep'];
        $row->left_forearm = $payload['bodyParts']['leftForearm'];
        $row->right_forearm = $payload['bodyParts']['rightForearm'];
        $row->left_thigh = $payload['bodyParts']['leftThigh'];
        $row->right_thigh = $payload['bodyParts']['rightThigh'];
        $row->left_calf = $payload['bodyParts']['leftCalf'];
        $row->right_calf = $payload['bodyParts']['rightCalf'];
        $row->save();
        $this->deletePhotos($row);
        $this->insertPhotos($payload, $row);
    }

    /**
     * @param array $payload
     * @throws Throwable
     */
    public function create(array $payload)
    {
        $row = BodyProgressModel::forceCreate([
            'order_id' => request()->get('order')->id,
            'folder_key' => Str::uuid(),
            'weight' => $payload['weight'],
            'neck' => $payload['bodyParts']['neck'],
            'shoulders' => $payload['bodyParts']['shoulders'],
            'bust' => $payload['bodyParts']['bust'],
            'chest' => $payload['bodyParts']['chest'],
            'waist' => $payload['bodyParts']['waist'],
            'abs' => $payload['bodyParts']['abs'],
            'hips' => $payload['bodyParts']['hips'],
            'left_bicep' => $payload['bodyParts']['leftBicep'],
            'right_bicep' => $payload['bodyParts']['rightBicep'],
            'left_forearm' => $payload['bodyParts']['leftForearm'],
            'right_forearm' => $payload['bodyParts']['rightForearm'],
            'left_thigh' => $payload['bodyParts']['leftThigh'],
            'right_thigh' => $payload['bodyParts']['rightThigh'],
            'left_calf' => $payload['bodyParts']['leftCalf'],
            'right_calf' => $payload['bodyParts']['rightCalf'],
            'heart_rate' => $payload['restingHeartRate']
        ]);
        $this->insertPhotos($payload, $row);
    }

    /**
     * @param string $data
     * @param string $directory
     * @return string
     * @throws Throwable
     */
    public function getImagePath(string $data, string $directory): string
    {
        $content = base64_decode($data);
        $path = $directory . '/' . Str::random(20) . '.' . request()->get('mime');
        Storage::disk('progresses')->put($path, $content);
        return $path;
    }

    /**
     * @param array $payload
     * @param BodyProgressModel $row
     * @throws Throwable
     */
    private function insertPhotos(array $payload, BodyProgressModel $row)
    {
        $directory = Utility::getUserId() . '/' . $row->folder_key;
        Storage::disk('progresses')->deleteDirectory($directory);

        ProgressTracePhotoModel::forceCreate([
            'progress_id' => $row->id,
            'front_path' => $this->getImagePath($payload['tracingPhotos']['front']['data'], $directory),
            'back_path' => $this->getImagePath($payload['tracingPhotos']['back']['data'], $directory),
            'left_path' => $this->getImagePath($payload['tracingPhotos']['left']['data'], $directory),
            'right_path' => $this->getImagePath($payload['tracingPhotos']['right']['data'], $directory),
        ]);
    }

    /**
     * @param BodyProgressModel $row
     */
    private function deletePhotos(BodyProgressModel $row)
    {
        $row->photos()->delete();
    }

    /**
     * @param int $id
     */
    public function delete(int $id) {
        $row = BodyProgressModel::find($id);
        $this->deletePhotos($row);
        $row->delete();
    }

    /**
     * @return bool
     * @throws Throwable
     */
    public function canWrite(): bool
    {
        $order = $this->getUserLatestOrder();
        $latest_body_progress = $order->bodyProgresses()?->latest()?->first();
        if($latest_body_progress == null)
            return true;
        $tomorrow = Carbon::parse($latest_body_progress->created_at)->addDays(15);
        return Carbon::now()->greaterThanOrEqualTo($tomorrow);
    }

}
