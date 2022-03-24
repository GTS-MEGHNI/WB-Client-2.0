<?php

namespace Modules\Subscription\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class CoachModel extends Model
{
    use HasFactory, Notifiable;

    protected $connection = 'coaches';
    protected $table = 'users';
    protected $primaryKey = 'id';
    public $incrementing = true;


    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\Subscription\Database\factories\CoachModelFactory::new();
    }
}
