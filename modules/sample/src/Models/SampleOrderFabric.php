<?php

namespace SkylarkSoft\GoRMG\Sample\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricNature;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;

class SampleOrderFabric extends Model
{
    use SoftDeletes;

    protected $table = 'sample_order_fabrics';

    protected $fillable = [
        'sample_order_requisition_id',
        'fabric_nature_id',
        'fabric_source_id',
        'supplier_id',
        'delivery_id',
        'delivery_date',
    ];

    public function sampleOrderRequisition(): BelongsTo
    {
        return $this->belongsTo(SampleOrderRequisition::class, 'sample_order_requisition_id')->withDefault();
    }

    public function fabricNature(): BelongsTo
    {
        return $this->belongsTo(FabricNature::class, 'fabric_nature_id')->withDefault();
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'delivery_id')->withDefault();
    }
}
