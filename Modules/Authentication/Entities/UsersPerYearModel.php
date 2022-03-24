<?php

namespace Modules\Authentication\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static where(string $string, string $string1, int $year)
 * @method static create(array $array)
 */
class UsersPerYearModel extends Model
{
    use HasFactory;

    protected $fillable = ['year', 'count'];
    protected $table = 'users_per_years';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected static function newFactory()
    {
        //return \Modules\Authentication\Database\factories\UsersPerYearModelFactory::new();
    }
}
