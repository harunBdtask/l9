<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models\Bookings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoiceDetails;

class FabricBookingDetailsBreakdown extends Model
{
    use SoftDeletes;

    protected $table = 'fabric_booking_details_breakdown';

    protected $fillable = [
        'booking_id',
        'job_no',
        'po_no',
        'body_part_value',
        'body_part_id',
        'garments_item_id',
        'garments_item_name',
        'fabric_composition_id',
        'fabric_composition_value',
        'style_name',
        'color_type_id',
        'color_type_value',
        'dia_type',
        'dia_type_value',
        'construction',
        'composition',
        'gsm',
        'item_color',
        'gmt_color',
        'color',
        'color_id',
        'size',
        'size_id',
        'dia',
        'dia_fin_type',
        'process_loss',
        'balance_qty',
        'wo_qty',
        'adj_qty',
        'moq_qty',
        'kg_cr',
        'actual_wo_qty',
        'uom_value',
        'uom',
        'rate',
        'amount',
        'total_qty',
        'sample_fabric_qty',
        'inspection_sample_qty',
        'remarks',
        'remarks2',
        'pantone',
        'yards',
        'cuttable_dia',
        'code',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'avl_stock_qty'
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(FabricBooking::class, 'booking_id')->withDefault();
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class, 'job_no', 'job_no')->withDefault();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'job_no', 'job_no')->withDefault();
    }

    public function fabricBooking(): BelongsTo
    {
        return $this->belongsTo(FabricBooking::class, 'booking_id')->withDefault();
    }

    public function getAvlStockQtyAttribute()
    {
        $fabricStock = FabricVirtualStock::query()
            ->where('composition', $this->attributes['composition'])
            ->where('construction', $this->attributes['construction'])
            ->where('gsm', $this->attributes['gsm'])
            ->where('gmt_color', $this->attributes['gmt_color'])
            ->where('item_color', $this->attributes['item_color'])
            ->first();

        return $fabricStock->stock ?? 0;
    }

    public function piDetails()
    {
        return $this->hasMany(ProformaInvoiceDetails::class, 'booking_details_id');
    }
}
