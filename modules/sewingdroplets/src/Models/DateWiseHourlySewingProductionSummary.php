<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class DateWiseHourlySewingProductionSummary extends Model
{
    use SoftDeletes;

    protected $table = 'date_wise_hourly_sewing_production_summaries';

    protected $fillable = [
        'production_date',
        'summary_data',
        'factory_id'
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'summary_data' => Json::class,
    ];

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id')->withDefault();
    }
}
