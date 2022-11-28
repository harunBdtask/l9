<?php

namespace SkylarkSoft\GoRMG\Knitting\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class YarnAllocation extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $fillable = [
        'allocation_no',
        'factory_id',
        'buyer_id',
        'booking_id',
        'booking_no',
        'uniq_id',
        'order_number',
        'style_name',
        'allocation_date',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public static function booted()
    {
        static::created(function ($model) {
            $generate = str_pad($model->id, 5, "0", STR_PAD_LEFT);
            $model->allocation_no = getPrefix() . 'YAL-' . date('y') . '-' . $generate;
            $model->save();
        });
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->select('id', 'factory_name', 'factory_address')->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->select('id', 'name')->withDefault();
    }

    public function details(): HasMany
    {
        return $this->hasMany(YarnAllocationDetail::class, 'yarn_allocation_id');
    }

    public function bookingDetails(): HasMany
    {
        return $this->hasMany(YarnAllocationBookingDetail::class, 'yarn_allocation_id');
    }

    public function planInfo(): MorphMany
    {
        return $this->morphMany(PlanningInfo::class, 'programmable');
    }
}
