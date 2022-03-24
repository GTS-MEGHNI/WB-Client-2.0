<?php

namespace Modules\Food\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @property mixed $id
 * @property mixed $step
 * @property mixed $order
 * @method static find($id)
 * @method static forceCreate(array $array)
 */
class FoodPreparationStepModel extends Model
{
    use HasFactory;

    protected $connection = 'food';
    protected $table = 'preparation_steps';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\Food\Database\factories\RecipePreparationStepModelFactory::new();
    }

    #[ArrayShape(['id' => "mixed", 'order' => "mixed", 'content' => "mixed"])]
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'order' => $this->order,
            'content' => $this->step
        ];
    }


}
