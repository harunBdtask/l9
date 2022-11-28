<?php

namespace SkylarkSoft\GoRMG\Sample\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsSample;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class SampleOrderRequisitionDetails extends Model
{
    use SoftDeletes;
    use HasJsonRelationships;

    protected $table = 'sample_order_requisition_details';

    protected $fillable = [
        'sample_order_requisition_id',
        'sample_id',
        'gmts_item_id',
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

    public function sample(): BelongsTo
    {
        return $this->belongsTo(GarmentsSample::class, 'sample_id')->withDefault();
    }

    public function gmtsItem(): BelongsTo
    {
        return $this->belongsTo(GarmentsItem::class, 'gmts_item_id')->withDefault();
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'details->gmts_size_id')->withDefault();
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'details->gmts_color_id')->withDefault();
    }
}
