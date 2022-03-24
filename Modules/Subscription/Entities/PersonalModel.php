<?php

namespace Modules\Subscription\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\ArrayShape;
use Throwable;

/**
 * @method static forceCreate(array $array)
 * @property mixed $first_name
 * @property mixed $last_name
 * @property mixed $country
 * @property mixed $province
 */
class PersonalModel extends Model
{
    use HasFactory;

    protected $table = 'personals';
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
        // return \Modules\Checkout\Database\factories\PersonalModelFactory::new();
    }

    /**
     * @throws Throwable
     */
    #[ArrayShape(['firstname' => "mixed", 'lastname' => "mixed", 'country' => "mixed", 'province' => "mixed", 'avatar' => "mixed"])]
    public function toArray(): array
    {
        return [
            'firstname' => $this->first_name,
            'lastname' => $this->last_name,
            'country' => $this->country,
            'province' => $this->province,
        ];
    }
}
