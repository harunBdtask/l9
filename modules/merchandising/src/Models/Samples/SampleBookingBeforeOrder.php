<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models\Samples;

use Illuminate\Database\Eloquent\Relations\HasMany;

class SampleBookingBeforeOrder extends SampleBooking
{
    protected $table = 'sample_booking_before_orders';

    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $model->booking_no = getPrefix() . 'FBBO-' . date('y') . '-' . str_pad($model->id, 6, '0', STR_PAD_LEFT);
            $model->save();
        });
    }

    public function details(): HasMany
    {
        return $this->hasMany(SampleBookingBeforeOrderDetail::class, 'sample_booking_id');
    }
}