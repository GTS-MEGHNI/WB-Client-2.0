<?php

namespace Modules\Notification\Entities;

use App\Utility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\Pure;

/**
 * @method static forceCreate(array $array)
 * @method static latest()
 * @method static where(... $params)
 * @method static find(mixed $id)
 * @property mixed $id
 * @property mixed $new
 * @property mixed $created_at
 * @property mixed $type
 * @property mixed $content
 * @property mixed $link
 */
class NotificationModel extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = ['user_id'];

    protected static function newFactory()
    {
       // return \Modules\Notification\Database\factories\NotificationModelFactory::new();
    }

    #[Pure] public function toArray(): array
    {
        return Utility::remove_array_shape_tag([
            'id' => $this->id,
            'isNew' => boolval($this->new),
            'createdAt' => $this->created_at->timestamp,
            'type' => $this->type,
            'content' => $this->content,
            'link' => $this->link
        ]);
    }
}
