<?php

namespace Modules\Plans\Entities;

use App\Utility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @property mixed $program
 * @property mixed $multiple
 * @property mixed $models
 * @method static where(array $array)
 */
class ProgramModel extends Model
{
    use HasFactory;

    protected $connection = 'plans';
    protected $table = 'programs';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [];

    protected static function newFactory()
    {
        // return \Modules\Plans\Database\factories\PlanModelFactory::new();
    }

    public function models() : HasMany {
        return $this->hasMany(ProgramTypeModel::class, 'program_id', 'id');
    }

    #[ArrayShape(['dietProgram' => "mixed", 'isMultiGroup' => "mixed", 'description' => "string", 'groups' => "null", 'plans' => "null"])]
    public function toArray(): array
    {
        return Utility::array_filter([
            'dietProgram' => $this->program,
            'isMultiGroup' => $this->multiple,
            'planGroups' => $this->models->toArray()
        ]);
    }

    public function getMultipleAttribute($value) : bool {
        return boolval($value);
    }
}
