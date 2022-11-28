<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models\Samples;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricNature;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\Team;

class SampleBookingConfirmOrder extends SampleBooking
{
    protected $table = 'sample_booking_confirm_orders';


    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $model->booking_no = getPrefix() . 'FBCO-' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
        });
    }

    public function details(): HasMany
    {
        return $this->hasMany(SampleBookingConfirmOrderDetail::class, 'sample_booking_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withDefault();
    }

    public function fabricNature(): BelongsTo
    {
        return $this->belongsTo(FabricNature::class, 'fabric_nature_id')->withDefault();
    }

    public function dealingMerchant(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'dealing_merchant_id')->withDefault();
    }
}
