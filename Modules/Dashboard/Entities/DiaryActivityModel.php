<?php

namespace Modules\Dashboard\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @method static forceCreate(array $array)
 * @property mixed $activity
 */
class DiaryActivityModel extends Model
{
    use HasFactory;

    protected $connection = 'metrics';
    protected $table = 'diaries_activities';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\Dashboard\Database\factories\DiaryActivityModelFactory::new();
    }

    #[ArrayShape(['activity' => "mixed"])]
    public function toArray(): array
    {
        return [
            'activity' => $this->activity
        ];
    }
}
