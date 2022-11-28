<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FundRequisitionAccountApproval extends Model
{
    protected $table = 'bf_fund_requisition_account_approvals';
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
