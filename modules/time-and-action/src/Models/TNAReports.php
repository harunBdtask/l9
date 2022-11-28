<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\TimeAndAction\Services\TNADelayEarlyCalculator;

class TNAReports extends Model
{
    protected $table = 'tna_reports';

    protected $fillable = [
        'factory_id',
        'buyer_id',
        'task_id',
        'order_id',
        'po_id',
        'based_on',
        'lead_time',
        'execution_days',
        'deadline',
        'notice_before',
        'notice_before_date',
        'notice_before_notified',
        'task_sequence',
        'start_date',
        'finish_date',
        'actual_start_date',
        'actual_finish_date',
        'early_start',
        'early_finish',
        'delay_start',
        'delay_finish',
        'comment_start',
        'comment_finish',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($task) {
            $calculator = new TNADelayEarlyCalculator($task);

            $startEarly = $calculator->startEarly();
            $finishEarly = $calculator->finishEarly();
            $startDelay = $calculator->startDelay();
            $finishDelay = $calculator->finishDelay();

            $task->early_start = $startEarly > 0 ? $startEarly : null;
            $task->early_finish = $finishEarly > 0 ? $finishEarly : null;
            $task->delay_start = $startDelay > 0 ? -1 * $startDelay : null;
            $task->delay_finish = $finishDelay > 0 ? -1 * $finishDelay : null;
        });
    }

    // Report based on po wise or order wise.
    const PO_WISE = 1;
    const ORDER_WISE = 2;

    public function setActualStartDateAttribute($value)
    {
        $value ? $this->attributes['actual_start_date'] = Carbon::parse($value)->format('Y-m-d')
               : $this->attributes['actual_start_date'] = null;
    }

    public function setActualFinishDateAttribute($value)
    {
        $value ? $this->attributes['actual_finish_date'] = Carbon::parse($value)->format('Y-m-d')
               : $this->attributes['actual_finish_date'] = null;

    }

    public function setStartDateAttribute($value)
    {
        $value ? $this->attributes['start_date'] = Carbon::parse($value)->format('Y-m-d')
               : $this->attributes['start_date'] = null;
    }

    public function setFinishDateAttribute($value)
    {
        $value ? $this->attributes['finish_date'] = Carbon::parse($value)->format('Y-m-d')
               : $this->attributes['finish_date'] = null;

    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(TNATask::class)->withDefault();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class)->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class)->withDefault();
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id')->withDefault();
    }
}
