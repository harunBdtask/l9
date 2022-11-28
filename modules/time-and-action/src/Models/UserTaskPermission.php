<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Models;

use App\Models\BelongsToBuyer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTaskPermission extends Model
{
    use SoftDeletes, BelongsToBuyer;

    protected $table = 'user_wise_task_permission';
    protected $primaryKey = 'id';
    protected $fillable = [
        'buyer_id',
        'task_id',
        'plan_date_choice',
        'actual_date_choice',
        'created_by',
        'factory_id'
    ];

    const PLAN_DATE_CHOICE = 0;
    const ACTUAL_DATE_CHOICE = 1;

    public function task(): BelongsTo
    {
        return $this->belongsTo(TNATask::class, 'task_id');
    }
}
