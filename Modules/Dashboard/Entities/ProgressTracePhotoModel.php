<?php

namespace Modules\Dashboard\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static forceCreate(array $array)
 */
class ProgressTracePhotoModel extends Model
{
    use HasFactory;

    protected $connection = 'metrics';
    protected $table = 'progresses_photos';
    protected $primaryKey = 'id';
    public $incrementing = true;


    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\Dashboard\Database\factories\ProgressTracePhotoModelFactory::new();
    }

    public function toArray(): array
    {
        return [
            'front' => [
                'imageUrl' => $this->front_path,
            ],
            'back' => [
                'imageUrl' => $this->back_path,
            ],
            'left' => [
                'imageUrl' => $this->left_path,
            ],
            'right' => [
                'imageUrl' => $this->right_path,
            ]
        ];
    }

    public function getFrontPathAttribute($value): string
    {
        return env('APP_URL') . '/progresses/' . $value;
    }

    public function getBackPathAttribute($value): string
    {
        return env('APP_URL') . '/progresses/' . $value;

    }

    public function getLeftPathAttribute($value): string
    {
        return env('APP_URL') . '/progresses/' . $value;
    }

    public function getRightPathAttribute($value): string
    {
        return env('APP_URL') . '/progresses/' . $value;
    }
}
