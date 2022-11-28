<?php

namespace SkylarkSoft\GoRMG\Sample\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\SampleLine;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class SampleProductionDetails extends Model
{
    use SoftDeletes;
    use HasJsonRelationships;

    protected $table = 'sample_production_details';

    protected $fillable = [
        'sample_production_id',
        'sample_processing_id',
        'sample_order_requisition_id',
        'sample_sewing_line_id',
        'gmts_color_id',
        'gmts_size_id',
        'details',
    ];

    protected $casts = [
        'details' => Json::class,
    ];

    public function sampleLine(): BelongsTo
    {
        return $this->belongsTo(SampleLine::class, 'sample_sewing_line_id')->withDefault();
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'gmts_color_id')->withDefault();
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'gmts_size_id')->withDefault();
    }
}
