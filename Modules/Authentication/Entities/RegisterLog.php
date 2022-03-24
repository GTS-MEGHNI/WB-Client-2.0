<?php

namespace Modules\Authentication\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

/**
 * @method static forceCreate(array $array)
 * @method static where(array $array)
 * @method static updateOrCreate(array $array, array $array1)
 * @method static find(string|null $bearerToken)
 * @property mixed $created_at
 * @property mixed $attempts_left
 * @property mixed $first_name
 */
class RegisterLog extends Model
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'token';
    public $incrementing = false;

    protected $fillable = ['first_name', 'last_name', 'email', 'passcode', 'password'];

    protected static function newFactory()
    {
        //return \Modules\Authentication\Database\factories\RegisterLogFactory::new();
    }
}
