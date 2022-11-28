<?php


namespace SkylarkSoft\GoRMG\Merchandising\Models\Bookings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\SystemSettings\Models\Brand;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;

class FabricServiceBookingDetail extends Model
{
    protected $table = 'fabric_service_booking_details';

    protected $fillable = [
        'budget_id',
        'service_booking_id',
        'style_name',
        'po_no',
        'fabric_description',
        'gmts_color_id',
        'item_color_id',
        'labdip_no',
        'lot',
        'yarn_count_id',
        'yarn_composition_id',
        'brand_id',
        'mc_dia', // number
        'finish_dia', // number
        'finish_gsm', // number
        'stich_length', // string
        'mc_gauge', // number
        'uom_id',
        'balance_qty', // 4 digits after decimal
        'wo_qty', // 4 digits after decimal
        'rate', // 4 digits after decimal
        'amount', // 4 digits after decimal
        'delivery_date', // date
        'status',
    ];

    protected $appends = [
        'budget_qty'
    ];

    public function getBudgetQtyAttribute()
    {
        $costing = BudgetCostingDetails::where('budget_id', $this->attributes['budget_id'])
            ->whereType('fabric_costing')
            ->first();

        return collect($costing->details['details']['fabricForm'])
            ->where('fabric_composition_value', $this->attributes['fabric_description'])
            ->pluck('greyConsForm.details')
            ->flatten(1)
            ->where('po_no', $this->attributes['po_no'])
            ->where('color_id', $this->attributes['item_color_id'])
            ->sum('total_qty');
    }

    public function serviceBooking(): BelongsTo
    {
        return $this->belongsTo(FabricServiceBooking::class, 'service_booking_id');
    }

    public function yarnCount(): BelongsTo
    {
        return $this->belongsTo(YarnCount::class, 'yarn_count_id')->withDefault([
            'yarn_count' => 'N\A',
        ]);
    }

    public function yarnComposition(): BelongsTo
    {
        return $this->belongsTo(YarnComposition::class, 'yarn_composition_id')->withDefault([
            'yarn_composition' => 'N\A',
        ]);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id')->withDefault([
            'brand_name' => 'N\A',
        ]);
    }

    public function garmentsColor(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'gmts_color_id')->withDefault();
    }

    public function itemColor(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'item_color_id')->withDefault();
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasurement::class, 'uom_id')->withDefault();
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class, 'budget_id')->withDefault();
    }
}
