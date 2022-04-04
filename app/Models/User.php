<?php

namespace App\Models;

use App\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Laravel\Sanctum\HasApiTokens;
use Modules\Authentication\Entities\UserContactModel;
use Modules\Authentication\Entities\UserSettingsModel;
use Modules\Authentication\Services\UserIDGeneratorService;
use Throwable;

/**
 * @method static create(array $array)
 * @method static find($user_id)
 * @method static where(array $array)
 * @method static forceCreate(array $array)
 * @property mixed $first_name
 * @property mixed $last_name
 * @property mixed $birth_date
 * @property mixed $gender
 * @property mixed $weeding_date
 * @property mixed $avatar
 * @property mixed $email
 * @property mixed $avatar_key
 * @property mixed $contact
 * @property mixed $delivery
 * @property mixed $settings
 * @property mixed $provider
 * @property mixed $wedding_date
 * @property mixed $user_id
 * @property mixed $cart
 * @property mixed $id
 * @property mixed $password
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = ['status', 'last_seen_at', 'updated_at'];

    public function contact(): hasOne
    {
        return $this->hasOne(UserContactModel::class, 'id');
    }

    public function settings(): hasOne
    {
        return $this->hasOne(UserSettingsModel::class, 'id');
    }


    /** @noinspection PhpUnused */
    public function getAvatarAttribute($value): ?string
    {
        if ($this->avatar_key == null)
            return $value;
        else
            return env('APP_URL') . '/users/' . $value;
    }

    /** @noinspection PhpArrayShapeAttributeCanBeAddedInspection */
    public function toArray(): array
    {
        return [
            'avatar' => $this->avatar,
            'firstname' => $this->first_name,
            'lastname' => $this->last_name,
            'dateOfBirth' => $this->birth_date,
            'gender' => $this->gender,
            'email' => $this->email,
        ];
    }

    /**
     * @return array
     * @throws Throwable
     * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
     */
    public function resource() :array {
        return [
            'token' => Auth::generateToken($this),
            'personal' => $this->toArray(),
            'contact' => $this->contact->toArray(),
            'settings' => $this->settings->toArray(),
        ];
    }

    public function googleAccount(): bool
    {
        return $this->provider == 'google';
    }

    public function facebookAccount(): bool
    {
        return $this->provider == 'facebook';
    }

    public static function addNewUser(array $payload): static
    {
        $user = self::forceCreate([
            'id' => (new UserIDGeneratorService())->generateID(),
            'first_name' => $payload['first_name'],
            'last_name' => $payload['last_name'],
            'email' => $payload['email'],
            'password' => Arr::has($payload, 'password') ?
                $payload['password'] : null,
            'social_id' => Arr::has($payload, 'social_id') ? $payload['social_id'] : null,
            'avatar' => Arr::has($payload, 'avatar') ? $payload['avatar'] : null,
            'provider' => Arr::has($payload, 'provider') ? $payload['provider'] : null,
        ]);
        UserContactModel::forceCreate(['id' => $user->id]);
        UserSettingsModel::forceCreate([
            'id' => $user->id,
            'newsletter' => Arr::has($payload, 'newsletter') ? $payload['newsletter'] :
                false
        ]);
        return $user;
    }
}
