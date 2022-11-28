<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\Models\BelongsToFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Knitting\Traits\CommonBooted;

class TrimsSensitivityVariable extends Model
{
    use SoftDeletes;
    use CommonBooted;
    use BelongsToFactory;

    const VARIABLES = [
        1 => 'Yes',
        2 => 'No',
    ];

    protected $table = 'trims_sensitivity_validations';
    protected $primaryKey = 'id';
    protected $fillable = [
        'factory_id',
        'sensitivity_variable',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'sensitivity_variable_value',
    ];

    public function getSensitivityVariableValueAttribute(): ?string
    {
        return $this->attributes['sensitivity_variable']
            ? self::VARIABLES[$this->attributes['sensitivity_variable']]
            : null;
    }
}
