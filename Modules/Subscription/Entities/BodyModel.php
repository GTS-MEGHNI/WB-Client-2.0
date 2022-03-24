<?php

namespace Modules\Subscription\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @method static forceCreate(array $array)
 * @property mixed $gender
 * @property mixed $type
 * @property mixed $age
 * @property mixed $weight
 * @property mixed $height
 */
class BodyModel extends Model
{
    use HasFactory;

    protected $table = 'bodies';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(env('DB_CONNECTION_SUBSCRIPTIONS'));
    }

    protected static function newFactory()
    {
        //return \Modules\Checkout\Database\factories\BodyModelFactory::new();
    }

    #[ArrayShape(['gender' => "mixed", 'type' => "mixed", 'age' => "mixed", 'weight' => "mixed", 'height' => "mixed"])]
    public function toArray(): array
    {
        return [
            'gender' => $this->gender,
            'age' => $this->age,
            'weight' => $this->weight,
            'height' => $this->height
        ];
    }

}
