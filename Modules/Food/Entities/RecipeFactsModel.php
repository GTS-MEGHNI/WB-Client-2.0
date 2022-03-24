<?php

namespace Modules\Food\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecipeFactsModel extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $connection = 'food';
    protected $table = 'recipes_facts';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected static function newFactory()
    {
        //return \Modules\Food\Database\factories\RecipeFactsModelFactory::new();
    }

    public function toArray()
    {
        
    }
}
