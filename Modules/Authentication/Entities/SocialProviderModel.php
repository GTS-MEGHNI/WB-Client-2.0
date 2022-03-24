<?php

namespace Modules\Authentication\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SocialProviderModel extends Model
{
    use HasFactory;

    protected $connection = 'users';
    protected $table = 'users_social_providers';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Authentication\Database\factories\SocialProviderModelFactory::new();
    }
}
