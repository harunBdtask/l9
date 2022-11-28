<?php

namespace SkylarkSoft\GoRMG\Sample\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class SampleOrderAccessoriesDetails extends Model
{
    use SoftDeletes;
    use HasJsonRelationships;

    protected $table = 'sample_order_accessories_details';

    protected $fillable = [
        'sample_order_requisition_id',
        'item_group_id',
        'details',
        'calculations',
    ];

    protected $casts = [
        'details' => Json::class,
        'calculations' => Json::class,
    ];

    public function sampleOrderRequisition(): BelongsTo
    {
        return $this->belongsTo(SampleOrderRequisition::class, 'sample_order_requisition_id')->withDefault();
    }

    public function itemGroup(): BelongsTo
    {
        return $this->belongsTo(ItemGroup::class, 'item_group_id')->withDefault();
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'details->supplier_id')->withDefault();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'details->gmts_color_id')->withDefault();
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'details->size_id')->withDefault();
    }

    public function unitOfMeasurement()
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'details->item_group_uom_id')->withDefault();
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'details->delivery_id')->withDefault();
    }
}
