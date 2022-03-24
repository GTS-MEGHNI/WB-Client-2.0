<?php

namespace Modules\Dashboard\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static forceCreate(array $array)
 */
class DiaryFeelingModel extends Model
{
    use HasFactory;

    protected $connection = 'metrics';
    protected $table = 'diaries_feelings';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\Dashboard\Database\factories\DiaryFeelingModelFactory::new();
    }

}
