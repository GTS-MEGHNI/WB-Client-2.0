<?php

namespace Modules\Food\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\ArrayShape;
use Modules\Food\Traits\Food;

/**
 * @method static forceCreate(array $array)
 * @property mixed $full_path
 * @property mixed $thumbnail_path
 * @property mixed $food_id
 */
class FoodPictureModel extends Model
{
    use HasFactory, Food;

    protected $fillable = [];

    protected $connection = 'food';
    protected $table = 'food_pictures';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected static function newFactory()
    {
        //return \Modules\Food\Database\factories\FoodPictureModelFactory::new();
    }

    #[ArrayShape(['fullSizeUrl' => "mixed", 'thumbnailUrl' => "mixed"])] public function toArray(): array
    {
        return [
            'fullSizeUrl' => $this->full_path,
            'thumbnailUrl' => $this->thumbnail_path
        ];
    }

    public function getFullPathAttribute($value): string
    {
        return env('APP_URL') . '/food/'.$this->getFolderByFoodID($this->food_id).'/' . $this->food_id . '/' . $value;
    }

    public function getThumbnailPathAttribute($value): string
    {
        return env('APP_URL') . '/food/'.$this->getFolderByFoodID($this->food_id).'/' . $this->food_id . '/' . $value;
    }


}
