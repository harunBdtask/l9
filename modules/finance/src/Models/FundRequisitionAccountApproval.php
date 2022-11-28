<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FundRequisitionAccountApproval extends Model
{
    protected $table = 'fund_requisition_account_approvals';
    protected $primaryKey = 'id';
    protected $fillable = [
        'requisition_id',
        'detail_id',
        'date',
        'approved_qty',
        'rate',
        'amount',
        'remarks'
    ];

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(FundRequisition::class, 'requisition_id')->withDefault();
    }

    public function detail(): BelongsTo
    {
        return $this->belongsTo(FundRequisitionDetail::class, 'detail_id')->withDefault();
    }
}
