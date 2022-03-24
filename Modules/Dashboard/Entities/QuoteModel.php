<?php

namespace Modules\Dashboard\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @method static where(array $array)
 * @method static whereIn(string $string, array $array)
 * @property mixed $author
 * @property mixed $content
 * @property mixed $path
 */
class QuoteModel extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = 'quotes';
    protected $primaryKey = 'id';
    public $incrementing = true;

    #[ArrayShape(['author' => "mixed", 'content' => "mixed", 'imageUrl' => "mixed"])]
    public function toArray(): array
    {
        return [
            'author' => $this->author,
            'content' => $this->content,
            'imageUrl' => $this->path
        ];
    }

    public function getPathAttribute($value): string
    {
        return env('APP_URL') . '/quotes/' . $value;
    }

    protected static function newFactory()
    {
        //return \Modules\Dashboard\Database\factories\QuoteModelFactory::new();
    }
}
