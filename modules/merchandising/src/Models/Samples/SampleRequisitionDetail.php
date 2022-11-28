<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models\Samples;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsSample;

class SampleRequisitionDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sample_requisition_id',
        'sample_id',
        'gmts_item_id',
        'smv',
        'gmts_colors_id',
        'required_qty',
        'submission_date',
        'input_date',
        'expected_delivery_date',
        'delivery_date',
        'details',
        'calculation',
        'image_path',
    ];

    protected $casts = [
        'gmts_colors_id' => 'array',
        'details' => Json::class,
        'calculation' => Json::class,
    ];

    protected $appends = ['gmts_color_string'];

    public function getGmtsColorStringAttribute(): string
    {
        $gmts_color_arr = json_decode($this->attributes['gmts_colors_id'], true);
        return Color::query()
            ->whereIn('id', $gmts_color_arr)
            ->pluck('name')
            ->implode(', ');
    }

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(SampleRequisition::class, 'sample_requisition_id');
    }

    public function sample(): BelongsTo
    {
        return $this->belongsTo(GarmentsSample::class, 'sample_id')->withDefault();
    }

    public function gmtsItem(): BelongsTo
    {
        return $this->belongsTo(GarmentsItem::class, 'gmts_item_id');
    }
}
