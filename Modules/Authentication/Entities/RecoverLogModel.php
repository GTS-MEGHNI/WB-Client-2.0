<?php

namespace Modules\Authentication\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static forceCreate(array $array)
 * @method static where(array $array)
 * @method static find(string $token)
 */
class RecoverLogModel extends Model
{
    use HasFactory;

    protected $primaryKey = 'token';
    public $incrementing = false;

    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\Authentication\Database\factories\RecoverLogModelFactory::new();
    }

}
