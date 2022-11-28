<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models\Samples;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\BodyPart;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricNature;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsSample;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Services\DiaTypesService;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\BudgetService;
use SkylarkSoft\GoRMG\SystemSettings\Services\FabricSourceService;
use SkylarkSoft\GoRMG\Merchandising\Services\FabricDescriptionService;

class SampleRequisitionFabricDetail extends Model
{
    protected $table = 'sample_requisition_fabric_details';

    protected $fillable = [
        'requisition_id',
        'sample_id',
        'gmts_item_id',
        'body_part_id',
        'body_part_type',
        'fabric_nature_id',
        'color_type_id',
        'fabric_description_id',
        'fabric_source_id',
        'dia_type_id',
        'gsm',
        'gmts_colors_id', // Json
        'ld_no',
        'sensitivity',
        'uom_id',
        'req_qty',
        'rate',
        'total_qty',
        'total_amount',
        'img_src',
        'remarks',
        'details',  // Json
        'calculation', // Json
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'sample_id' => Json::class,
        'gmts_colors_id' => Json::class,
        'details' => Json::class,
        'calculation' => Json::class,
    ];

    protected $appends = [
        'image_url',
        'fabric_source',
        'dia_type',
        'gmts_color_string',
        'sensitivity_value',
        'umo_value',
        'fabric_composition_value',
    ];

    const SENSITIVITIES = [
        1 => "Contrast Color",
        2 => "As Per Gmts Color",
    ];

    public function getImageUrlAttribute()
    {
        $value = $this->attributes['img_src'];

        if (!$value) {
            return null;
        }

        return asset('storage/' . $value);
    }

    public function getFabricSourceAttribute()
    {
        return collect(FabricSourceService::fabricSource())
                ->where('id', $this->attributes['fabric_source_id'])
                ->first()['name'] ?? null;
    }

    public function getDiaTypeAttribute()
    {
        return collect(DiaTypesService::diaTypes())
                ->where('id', $this->attributes['dia_type_id'])
                ->first()['name'] ?? null;
    }

    public function getGmtsColorStringAttribute(): string
    {
        $gmts_color_arr = json_decode($this->attributes['gmts_colors_id'], true);
        return Color::query()
            ->whereIn('id', $gmts_color_arr)
            ->pluck('name')
            ->implode(', ');
    }

    public function getFabricCompositionValueAttribute(): ?string
    {
        return isset($this->attributes['fabric_description_id']) ?
            FabricDescriptionService::description($this->attributes['fabric_description_id']) : null;
    }

    public function getSensitivityValueAttribute(): string
    {
        return isset($this->attributes['sensitivity']) ? self::SENSITIVITIES[$this->attributes['sensitivity']] : '';
    }

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(SampleRequisition::class, 'requisition_id');
    }

    public function gmtsItem(): BelongsTo
    {
        return $this->belongsTo(GarmentsItem::class, 'gmts_item_id');
    }

    public function bodyPart(): BelongsTo
    {
        return $this->belongsTo(BodyPart::class, 'body_part_id')->withDefault();
    }

    public function fabricNature(): BelongsTo
    {
        return $this->belongsTo(FabricNature::class, 'fabric_nature_id')->withDefault();
    }

    public function colorType(): BelongsTo
    {
        return $this->belongsTo(ColorType::class, 'color_type_id')->withDefault();
    }

    public function sample(): BelongsTo
    {
        return $this->belongsTo(GarmentsSample::class, 'sample_id')->withDefault();
    }

    public function unitOfMeasurement(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'uom_id')->withDefault();
    }

    public function getUMOValueAttribute(): string
    {
        return BudgetService::UOM[$this->attributes['uom_id']] ?? '';
    }
}
