<?php

namespace Modules\Authentication\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @method static create(array $array)
 * @method static where(array $array)
 * @method static forceCreate(array $array)
 * @property mixed $email
 * @property mixed $phone
 * @property mixed $facebook
 * @property mixed $instagram
 * @property mixed $country
 * @property mixed $province
 * @property mixed $address
 */
class UserContactModel extends Model
{
    use HasFactory;

    protected $table = 'users_contacts';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected static function newFactory()
    {
        //return \Modules\Authentication\Database\factories\UserContactModelFactory::new();
    }

    /** @noinspection PhpArrayShapeAttributeCanBeAddedInspection */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'phone' => $this->phone,
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'country' => $this->country,
            'province' => $this->province,
            'address' => $this->address
        ];
    }
}
