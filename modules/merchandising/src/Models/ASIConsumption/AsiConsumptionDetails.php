<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models\ASIConsumption;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Merchandising\Services\FabricDescriptionService;
use SkylarkSoft\GoRMG\SystemSettings\Models\BodyPart;
use SkylarkSoft\GoRMG\SystemSettings\Models\EmbellishmentItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;

class AsiConsumptionDetails extends Model
{
    use HasFactory;

    protected $table = 'asi_consumption_details';
    protected $fillable = [
        'asi_consumption_id',
        'gmts_item_id',
        'group_id',
        'body_part_id',
        'embl_id',
        'type_id',
        'fabrication_id',
        'fabric_dia',
        'length',
        'width',
        'uom_id',
        'cons_per_pcs',
        'cons_per_dzn',
        'efficiency',
        'marker_type',
        'remarks',
    ];

    const groups = [
        'knit_fabric' => 'knit Fabric',
        'woven_fabric' => 'Woven Fabric'
    ];

    public function gmtsItem()
    {
        return $this->belongsTo(GarmentsItem::class, 'gmts_item_id')->withDefault();
    }

    public function embellishmentName()
    {
        return $this->belongsTo(EmbellishmentItem::class, 'embl_id')->withDefault();
    }

    public function embellishmentType()
    {
        return $this->belongsTo(EmbellishmentItem::class, 'type_id')->withDefault();
    }

    public function fabrication()
    {
        return $this->belongsTo(YarnComposition::class,'fabrication_id')->withDefault();
    }

    public function uom()
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'uom_id')->withDefault();
    }

    public function bodyPart(): BelongsTo
    {
        return $this->belongsTo(BodyPart::class, 'body_part_id')->withDefault();
    }
}
