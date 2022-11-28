<?php

namespace SkylarkSoft\GoRMG\Misdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use App\FactoryIdTrait;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;

class MonthlyEfficiencySummaryReport extends Model
{
    use FactoryIdTrait;

    protected $fillable = [
        'report_date',
        'floor_id',
        'line_id',
        'used_minutes',
        'produced_minutes',
        'factory_id',
        'factory_id'
    ];

    public function floor(): BelongsTo
    {
        return $this->belongsTo(Floor::class, 'floor_id')->withDefault();
    }

    public function line(): BelongsTo
    {
        return $this->belongsTo(Line::class, 'line_id')->withDefault();
    }
}
