<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\Casts\Json;
use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;

class SampleTrimsBooking extends Model
{
    use SoftDeletes, ModelCommonTrait;

    protected $fillable = [
        'booking_no',
        'factory_id',
        'location',
        'buyer_id',
        'booking_date',
        'delivery_date',
        'supplier_id',
        'booking_basis',
        'material_source',
        'pay_mode',
        'source',
        'attention',
        'currency',
        'exchange_rate',
        'delivery_to',
        'ready_to_approve',
        'remarks',
        'terms_and_condition',
        'unapprove_request',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    protected $appends = [
        'pay_mode_value',
        'material_source_value',
        'source_value',
    ];

    const PAY_MODE = [
        '1' => 'Credit',
        '2' => 'Import',
        '3' => 'In House',
        '4' => 'Within Group',
    ];

    const SOURCE = [
        '1' => 'Abroad',
        '2' => 'EPZ',
        '3' => 'Non-EPZ',
    ];

    const MATERIAL_SOURCE = [
        '1' => 'Purchase',
        '2' => 'Buyer Supplier',
    ];

    protected $casts = [
        'terms_and_condition' => Json::class
    ];

    public function getPayModeValueAttribute(): string
    {
        return self::PAY_MODE[$this->attributes['pay_mode']];
    }

    public function getMaterialSourceValueAttribute(): string
    {
        return self::MATERIAL_SOURCE[$this->attributes['material_source']];
    }

    public function getSourceValueAttribute(): string
    {
        return self::SOURCE[$this->attributes['source']];
    }

    public static function booted()
    {
        static::created(function ($model) {
            $generate = str_pad($model->id, 5, "0", STR_PAD_LEFT);
            $model->booking_no = getPrefix() . 'STB-' . date('y') . '-' . $generate;
            $model->save();
        });
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(SampleTrimsBookingDetail::class, 'sample_trims_booking_id', 'id');
    }
}
