<?php

namespace SkylarkSoft\GoRMG\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;

class FundRequisitionDetail extends Model
{
    protected $table = 'fund_requisition_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'requisition_id',
        'date',
        'dept_id',
        'item_id',
        'item_description',
        'uom',
        'existing_qty',
        'req_qty',
        'rate',
        'amount',
        'remarks',
        'approval_status',
        'purpose_id'
    ];

    const UOM = [
        1 => 'PCS',
        2 => 'KG',
        3 => 'DZN',
        4 => 'GRS',
    ];

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(FundRequisition::class, 'requisition_id')->withDefault();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(AcDepartment::class, 'dept_id')->withDefault();
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(ItemGroup::class, 'item_id')->withDefault();
    }

    public function accountApproval(): HasMany
    {
        return $this->hasMany(FundRequisitionAccountApproval::class, 'detail_id');
    }

    public function purpose(): BelongsTo
    {
        return $this->belongsTo(FundRequisitionPurpose::class, 'purpose_id')->withDefault();
    }

    public function uoms(): string
    {
        return self::UOM[$this->uom];
    }
}
