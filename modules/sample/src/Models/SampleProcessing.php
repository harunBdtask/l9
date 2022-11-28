<?php

namespace SkylarkSoft\GoRMG\Sample\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class SampleProcessing extends Model
{
    use SoftDeletes;

    protected $table = 'sample_processings';

    protected $fillable = [
        'process_id',
        'sample_id',
        'requisition_id',
        'buyer_id',
        'factory_id',
        'style_name',
        'ready_for_approve',
        'order_qty',
        'details',
        'total_calculation',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'details' => Json::class,
        'total_calculation' => Json::class,
    ];

    protected static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            $model->process_id = 'SPI-' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
        });

        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        self::deleted(function ($model) {
            if (in_array('deleted_by', $model->getFillable())) {
                DB::table($model->table)->where('id', $model->id)->update([
                    'deleted_by' => Auth::id(),
                ]);
            }
        });
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class)->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class)->withDefault();
    }

    public function processingDetails(): HasMany
    {
        return $this->hasMany(SampleProcessingDetails::class, 'sample_processing_id');
    }

    public function sampleProductionDetails(): HasMany
    {
        return $this->hasMany(SampleProductionDetails::class, 'sample_processing_id');
    }

    public function productions()
    {
        return $this->hasMany(SampleProduction::class, 'sample_processing_id');
    }
}
