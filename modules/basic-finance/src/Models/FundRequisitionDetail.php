<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;

class FundRequisitionDetail extends Model
{
    protected $table = 'bf_fund_requisition_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'requisition_id',
        'date',
        'unit_id',
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

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id')->withDefault();
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
