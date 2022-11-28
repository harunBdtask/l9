<?php

namespace SkylarkSoft\GoRMG\Sample\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\BodyPart;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricConstructionEntry;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class SampleOrderFabricDetails extends Model
{
    use SoftDeletes;
    use HasJsonRelationships;

    protected $table = 'sample_order_fabric_details';

    protected $fillable = [
        'sample_order_requisition_id',
        'sample_order_fabric_id',
        'body_part_id',
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

    public function sampleOrderFabric(): BelongsTo
    {
        return $this->belongsTo(SampleOrderFabric::class, 'sample_order_fabric_id')->withDefault();
    }

    public function bodyPart(): BelongsTo
    {
        return $this->belongsTo(BodyPart::class, 'body_part_id')->withDefault();
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'details->gmts_color_id')->withDefault();
    }

    public function colorType(): BelongsTo
    {
        return $this->belongsTo(ColorType::class, 'details->color_type_id')->withDefault();
    }

    public function fabricConstructionEntry(): BelongsTo
    {
        return $this->belongsTo(FabricConstructionEntry::class, 'details->construction')->withDefault();
    }

    public function newFabricComposition(): BelongsTo
    {
        return $this->belongsTo(NewFabricComposition::class, 'details->fabric_description')->withDefault();
    }
}
