<?php


namespace SkylarkSoft\GoRMG\Commercial\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Commercial\Constants\CommercialConstant;
use SkylarkSoft\GoRMG\Commercial\Options;

class B2BMarginLCAmendment extends Model
{
    use SoftDeletes;
    use ModelCommonTrait;

    protected $table = 'b_to_b_margin_lc_amendments';

    protected $fillable = [
        'b_to_b_margin_lc_id',
        'amendment_no',
        'amendment_value',
        'amendment_date',
        'lc_value',
        'value_changed_by',
        'last_shipment_date',
        'lc_expiry_date',
        'delivery_mode',
        'inco_term',
        'inco_term_place',
        'partial_shipment',
        'port_of_loading',
        'port_of_discharge',
        'pay_term',
        'tenor',
        'remarks',
        'bank_amendment_date',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function getPayTermAttribute($attribute): string
    {
        return Options::PAY_TERMS[$attribute] ?? '';
    }

    public function getDeliveryModeAttribute($attribute): string
    {
        return CommercialConstant::DELIVERY_MODE[$attribute] ?? '';
    }

    public function b2bMargin(): BelongsTo
    {
        return $this->belongsTo(B2BMarginLC::class, 'b_to_b_margin_lc_id')->withDefault();
    }

    public function updateB2BMarginLC()
    {
        $this->b2bMargin()->update([
            'amended' => $this->attributes['amendment_no'],
            'lc_value' => $this->attributes['lc_value'],
            'last_shipment_date' => $this->attributes['last_shipment_date'],
            'lc_expiry_date' => $this->attributes['lc_expiry_date'],
            'delivery_mode' => $this->attributes['delivery_mode'],
            'inco_term' => $this->attributes['inco_term'],
            'inco_term_place' => $this->attributes['inco_term_place'],
            'partial_shipment' => $this->attributes['partial_shipment'],
            'port_of_loading' => $this->attributes['port_of_loading'],
            'port_of_discharge' => $this->attributes['port_of_discharge'],
            'pay_term' => $this->attributes['pay_term'],
            'tenor' => $this->attributes['tenor'],
            'remarks' => $this->attributes['remarks'],
        ]);
    }
}
