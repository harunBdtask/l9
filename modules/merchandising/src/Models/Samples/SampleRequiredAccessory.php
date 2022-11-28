<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models\Samples;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsSample;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class SampleRequiredAccessory extends Model
{
    protected $table = 'sample_required_accessories';

    protected $fillable = [
        'sample_requisition_id',
        'sample_id',
        'gmts_item_id',
        'item_id',
        'brand_sup_ref',
        'description',
        'rate',
        'req_qty',
        'total_qty',
        'amount',
        'uom_id',
        'uom_value',
        'remarks',
        'image',
    ];

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(SampleRequisition::class, 'sample_requisition_id')->withDefault();
    }

    public function sample(): BelongsTo
    {
        return $this->belongsTo(GarmentsSample::class, 'sample_id')->withDefault();
    }

    public function garmentsItem(): BelongsTo
    {
        return $this->belongsTo(GarmentsItem::class, 'gmts_item_id')->withDefault();
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(ItemGroup::class, 'item_id')->withDefault();
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'uom_id')->withDefault();
    }
}