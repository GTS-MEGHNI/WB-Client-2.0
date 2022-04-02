<?php

namespace Modules\Dashboard\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static forceCreate(array $array)
 * @method static find(mixed $id)
 * @method static where(array $array)
 * @property mixed $id
 * @property mixed $folder_key
 */
class BodyProgressModel extends Model
{
    use HasFactory;

    protected $connection = 'metrics';
    protected $table = 'progresses';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\Dashboard\Database\factories\ProgressModelFactory::new();
    }

    public function photos(): HasOne {
        return $this->hasOne(ProgressTracePhotoModel::class, 'progress_id');
    }

    /** @noinspection PhpArrayShapeAttributeCanBeAddedInspection
     * @noinspection PhpUndefinedFieldInspection
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'date' => $this->created_at->timestamp,
            'weight' => $this->weight,
            'bodyParts' => [
                'neck' => $this->neck,
                'shoulders' => $this->shoulders,
                'bust' => $this->bust,
                'chest' => $this->chest,
                'waist' => $this->waist,
                'abs' => $this->abs,
                'hips' => $this->hips,
                'leftBicep' => $this->left_bicep,
                'rightBicep' => $this->right_bicep,
                'leftForearm' => $this->left_forearm,
                'rightForearm' => $this->right_forearm,
                'leftThigh' => $this->left_thigh,
                'rightThigh' => $this->right_thigh,
                'leftCalf' => $this->left_calf,
                'rightCalf' => $this->right_calf
            ],
            'tracingPhotos' => $this->photos->toArray(),
            'restingHeartRate' => $this->heart_rate
        ];
    }



}
