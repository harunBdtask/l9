<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models\WipReport;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\AssigningFactory;

class WipReport extends Model
{
    use HasFactory;

    protected $table = 'wip_reports';

    protected $fillable = [
        'assign_factory_id',
        'order_id',
        'style',
        'order_qty',
        'po_received_date',
        'po_issued_to_fty',
        'fabric_pi_recieved_date',
        'sc_issue_date',
        'revised_sc_issue_date',
        'color_breakdown_as_per_po',
        'customer_wise_po',
        'wip_date',
        'bulk_tp_received_date',
        'pcd',
        'po_delivery_date',
        'final_costing_approved',
        'costing_yy',
        'packing_info_upc',
        'ship_due_date',
        'image',
        'garments_item',
        'fabric_booking_details',
        'trims_booking_details',
        'sample_status',
        'copied_from',
        'wip_style'
    ];

    protected $casts = [
        'color_breakdown_as_per_po' => Json::class,
        'customer_wise_po' => Json::class,
        'fabric_booking_details' => Json::class,
        'trims_booking_details' => Json::class,
        'sample_status' => Json::class,
    ];

    public function factory()
    {
        return $this->belongsTo(AssigningFactory::class, 'assign_factory_id')->withDefault();
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id')->withDefault();
    }
}
