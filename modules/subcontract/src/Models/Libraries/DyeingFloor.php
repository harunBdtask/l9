<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\Libraries;

use App\Contracts\AuditAbleContract;
use App\Models\CommonModelTrait;
use App\Traits\AuditAble;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DyeingFloor extends Model implements AuditAbleContract
{
    use SoftDeletes;
    use CommonModelTrait;
    use AuditAble;

    const FLOOR_TYPES = [
        1 => 'Dyeing',
        2 => 'Dye Finishing',
    ];

    protected $table = 'dyeing_floors';
    protected $primaryKey = 'id';
    protected $fillable = [
        'type',
        'name',
        'attention',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'floor_type_value',
    ];

    public function getFloorTypeValueAttribute()
    {
        return self::FLOOR_TYPES[$this->attributes['type']];
    }

    public function moduleName(): string
    {
        return 'sub-contract-dyeing';
    }

    public function path(): string
    {
        return url("subcontract/dyeing-floor/$this->id/edit");
    }
}
