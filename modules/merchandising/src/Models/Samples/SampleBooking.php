<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models\Samples;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricNature;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;

abstract class SampleBooking extends Model
{
    use ModelCommonTrait, SoftDeletes;

    const PAY_MODES = [
        1 => 'Credit',
        2 => 'Import',
        3 => 'In House',
        4 => 'Withing Group'
    ];

    const FABRIC_SOURCES = [
        1 => 'Production',
        2 => 'Purchase',
        3 => 'Buyer Supplier',
        4 => 'Stock'
    ];

    protected $fillable = [
        'booking_no',
        'factory_id',
        'buyer_id',
        'supplier_id',
        'booking_date',
        'delivery_date',
        'currency_id',
        'pay_mode',
        'fabric_source',
        'exchange_rate',
        'team_leader_id',
        'dealing_merchant_id',
        'internal_ref',
        'attention',
        'is_short',
        'ready_to_approve',
        'created_by',
        'updated_by',
        'deleted_by',
        'fabric_nature_id',
        'style_name'
    ];


    protected $appends = [
        'pay_mode_value',
        'fabric_source_value'
    ];

    public function getPayModeValueAttribute(): string
    {
        $value = $this->pay_mode;
        if ( array_key_exists($value, self::PAY_MODES) ) return self::PAY_MODES[$value];
        return '';
    }

    public function getFabricSourceValueAttribute(): string
    {
        $value = $this->fabric_source;
        if ( array_key_exists($value, self::FABRIC_SOURCES) ) return self::FABRIC_SOURCES[$value];
        return '';
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withDefault();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withDefault();
    }

    public function fabricNature(): BelongsTo
    {
        return $this->belongsTo(FabricNature::class, 'fabric_nature_id')->withDefault();
    }
}