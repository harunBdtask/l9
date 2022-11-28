<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrOtApprovalDetail extends Model
{
    use SoftDeletes,ModelCommonTrait;

    protected $table = 'hr_ot_approval_details';

    protected $fillable = [
        'ot_approval_id',
        'ot_date',
        'ot_start_time',
        'ot_end_time',
        'ot_for',
        'department_id',
        'section_id',
        'approved_by',
        'remarks',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    const OT_FOR = [
        '1' => 'General',
        '2' => 'Night',
    ];

    const GENERAL_OT = 1;
    const NIGHT_OT = 2;

    public function otApproval(): BelongsTo
    {
        return $this->belongsTo(HrOtApproval::class, 'ot_approval_id')->withDefault();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(HrDepartment::class, 'department_id')->withDefault();
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(HrSection::class, 'section_id')->withDefault();
    }
}
