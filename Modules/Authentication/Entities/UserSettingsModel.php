<?php

namespace Modules\Authentication\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


/**
 * @method static where(array $array)
 * @method static forceCreate(array $array)
 * @property mixed $notifications
 * @property mixed $newsletter
 * @property mixed $language
 * @property mixed $theme
 */
class UserSettingsModel extends Model
{
    use HasFactory;

    protected $table = 'users_settings';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected static function newFactory()
    {
        //return \Modules\Authentication\Database\factories\UserSettingsModelFactory::new();
    }

    /** @noinspection PhpUnused */
    public function getNotificationsAttribute($value): bool
    {
        return $value == 1;
    }

    /** @noinspection PhpUnused */
    public function getNewsletterAttribute($value): bool
    {
        return $value == 1;
    }

    /** @noinspection PhpArrayShapeAttributeCanBeAddedInspection */
    public function toArray(): array
    {
        return [
            'notifications' => $this->notifications,
            'newsletter' => $this->newsletter,
            'theme' => $this->theme,
            'language' => $this->language
        ];
    }
}
