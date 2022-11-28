<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrLeaveSetting extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $table = 'hr_leave_settings';

    protected $fillable = [
        'employee_type',
        'leave_types_id',
        'number_of_days',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(HrLeaveTypes::class, 'leave_types_id', 'id')->withDefault();
    }
}
