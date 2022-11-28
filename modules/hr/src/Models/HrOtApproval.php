<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrOtApproval extends Model
{
    use SoftDeletes,ModelCommonTrait;

    protected $table = 'hr_ot_approvals';

    protected $fillable = [
        'ot_date',
        'ot_start_time',
        'ot_end_time',
        'ot_for',
        'file',
        'approved_by',
        'remarks',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    CONST OT_FOR = [
        '1' => 'General',
        '2' => 'Night',
    ];

    CONST GENERAL_OT = 1;
    CONST NIGHT_OT = 2;

    public function otApprovalDetails(): HasMany
    {
        return $this->hasMany(HrOtApprovalDetail::class, 'ot_approval_id');
    }
}
