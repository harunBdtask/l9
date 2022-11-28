<?php

namespace SkylarkSoft\GoRMG\Sample\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class SampleProduction extends Model
{
    use SoftDeletes;
    use HasJsonRelationships;

    protected $table = 'sample_productions';

    protected $fillable = [
        'sample_processing_id',
        'sample_order_requisition_id',
        'production_date',
        'merchant_id',
        'details',
        'total_calculation',
    ];

    protected $casts = [
        'details' => Json::class,
        'total_calculation' => Json::class,
    ];

    public function dealingMerchant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'merchant_id')->withDefault();
    }
}
