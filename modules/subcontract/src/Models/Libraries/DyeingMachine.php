<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\Libraries;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DyeingMachine extends Model
{
    use SoftDeletes;
    use CommonModelTrait;

    public const FLOOR_TYPES = [
        1 => 'Dyeing',
        2 => 'Dye Finishing',
    ];

    public const STATUS = [
        1 => 'Active',
        2 => 'Inactive',
    ];

    const ACTIVE = 1, INACTIVE = 2;

    public const MACHINE_TYPE = [
        1 => 'Fiber Dyeing M/C',
        2 => 'Yarn Dyeing M/C',
        3 => 'Knit Dyeing M/C',
        4 => 'Dye Finishing M/C',
    ];

    protected $table = 'dyeing_machines';
    protected $primaryKey = 'id';
    protected $fillable = [
        'floor_type',
        'name',
        'heating_rate',
        'maximum_working_pressure',
        'status',
        'type',
        'description',
        'cooling_rate',
        'maximum_working_temp',
        'capacity',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'floor_type_value',
        'machine_type_value',
        'status_value',
    ];

    public function getFloorTypeValueAttribute(): ?string
    {
        return isset($this->attributes['floor_type'])
            ? self::FLOOR_TYPES[$this->attributes['floor_type']]
            : null;
    }

    public function getMachineTypeValueAttribute(): ?string
    {
        return isset($this->attributes['type'])
            ? self::MACHINE_TYPE[$this->attributes['type']]
            : null;
    }

    public function getStatusValueAttribute(): ?string
    {
        return isset($this->attributes['status'])
            ? self::STATUS[$this->attributes['status']]
            : null;
    }

    public function scopeActive(Builder $query)
    {
        $query->where('status', self::ACTIVE);
    }

    public function scopeInActive(Builder $query)
    {
        $query->where('status', self::INACTIVE);
    }
}
