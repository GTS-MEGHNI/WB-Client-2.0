<?php

namespace Modules\Plans\Entities;

use App\Utility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $type
 * @property mixed $plans
 * @property mixed $program
 */
class ProgramTypeModel extends Model
{
    use HasFactory;

    protected $connection = 'plans';
    protected $table = 'models';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\Plans\Database\factories\ProgramTypeModelFactory::new();
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(ProgramModel::class, 'program_id', 'id');
    }

    public function plans(): HasMany
    {
        return $this->hasMany(ProgramPlanModel::class, 'model_id', 'id');
    }

    public function toArray(): array
    {
        return Utility::array_filter(
            [
                'dietType' => $this->type,
                'planGroupItems' => $this->plans->toArray()
            ]);
    }
}
