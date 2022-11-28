<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\BodyPart;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricNature;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;

class FabricCostDetails extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "fabric_cost_details";
    protected $primaryKey = "id";
    protected $fillable = [
        "quotation_id",
        "costing_multiplier",
        "garment_item_id",
        "body_part_id",
        "fabric_nature_id",
        "color_type_id",
        "fabric_composition_id",
        "fabric_source",
        "supplier_id",
        "consumption_basis",
        "uom",
        "dia_type",
        "gsm",
        "fabric_cons",
        "rate",
        "amount",
        "fabric_consumption_details",
        'status',
        "created_by",
        "updated_by",
    ];

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(PriceQuotation::class, "quotation_id")->withDefault();
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, "supplier_id")->withDefault();
    }

    public function bodyPart(): BelongsTo
    {
        return $this->belongsTo(BodyPart::class, "body_part_id");
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(GarmentsItem::class, "garment_item_id");
    }

    public function fabricNature(): BelongsTo
    {
        return $this->belongsTo(FabricNature::class, 'fabric_nature_id', 'id')->withDefault();
    }

    public function colorType(): BelongsTo
    {
        return $this->belongsTo(ColorType::class, 'color_type_id', 'id')->withDefault();
    }

    public function fabricComposition(): BelongsTo
    {
        return $this->belongsTo(NewFabricComposition::class, "fabric_composition_id")->withDefault();
    }
}
