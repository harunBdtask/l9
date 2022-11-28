<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FundRequisitionAuditApproval extends Model
{
    protected $table = 'bf_fund_requisition_audit_approvals';
    protected $primaryKey = 'id';
    protected $fillable = [
        'audit_date',
        'requisition_id',
        'detail_id',
        'comment',
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
