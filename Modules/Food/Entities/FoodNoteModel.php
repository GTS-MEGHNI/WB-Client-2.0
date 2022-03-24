<?php

namespace Modules\Food\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @property mixed $note
 * @property mixed $id
 * @method static forceCreate(array $array)
 */
class FoodNoteModel extends Model
{
    use HasFactory;
    protected $connection = 'food';
    protected $table = 'food_notes';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\Food\Database\factories\RecipeNoteModelFactory::new();
    }

    #[ArrayShape(['id' => "mixed", 'note' => "mixed"])] public function toArray(): array
    {
        return [
            'id' => $this->id,
            'note' => $this->note
        ];
    }

}
