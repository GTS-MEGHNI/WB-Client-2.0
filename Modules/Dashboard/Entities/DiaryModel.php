<?php

namespace Modules\Dashboard\Entities;

use App\Utility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static forceCreate(array $array)
 * @method static find(mixed $id)
 * @method static where(array $array)
 */
class DiaryModel extends Model
{
    use HasFactory;

    protected $connection = 'metrics';
    protected $table = 'diaries';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    protected static function newFactory()
    {
       // return \Modules\Dashboard\Database\factories\DiaryModelFactory::new();
    }


    public function activities(): HasMany {
        return $this->hasMany(DiaryActivityModel::class, 'diary_id');
    }

    public function feelings(): HasMany {
        return $this->hasMany(DiaryFeelingModel::class, 'diary_id');
    }

    public function toArray()
    {
        return Utility::array_filter([
            'id' => $this->id,
            'date' => $this->created_at->timestamp,
            'sleep' => $this->sleep,
            'energy' => $this->energy,
            'mood' => $this->mood,
            'activities' => $this->activities()->pluck('activity'),
            'feelings' => $this->feelings()->pluck('feeling'),
            'title' => $this->title,
            'note' => $this->note
        ]);
    }


}
