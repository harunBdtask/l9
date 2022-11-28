<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TNATemplateDetail extends Model
{
    protected $table = 'tna_template_details';

    protected $fillable = [
        'template_id',
        'task_id',
        'deadline',
        'execution_days',
        'start_from_day_no',
        'notice_before',
        'task_sequence',
        'status'
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(TNATask::class, 'task_id')->withDefault();
    }
}
