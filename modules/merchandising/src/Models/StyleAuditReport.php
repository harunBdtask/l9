<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StyleAuditReport extends Model
{
    protected $table = "style_audit_reports";
    protected $fillable = [
        'style_id',
        'order_qty',
        'order_value',
        'fabric_req_qty',
        'fabric_cost_value',
        'trims_cost_Value',
        'others_cost',
        'budget_value',
        'yarn_issue_qty',
        'yarn_issue_value',
        'fabric_booked_qty',
        'fabric_booked_value',
        'knitting_qty',
        'knitting_value',
        'dyeing_qty',
        'dyeing_value',
        'finish_fab_qty',
        'finish_fab_value',
        'cutting_qty',
        'cutting_value',
        'print_sent_qty',
        'print_sent_value',
        'print_receive_qty',
        'print_receive_value',
        'embr_sent_qty',
        'embr_sent_value',
        'embr_receive_qty',
        'embr_receive_value',
        'input_qty',
        'input_value',
        'sewing_qty',
        'sewing_value',
        'iron_qty',
        'iron_value',
        'poly_qty',
        'poly_value',
        'packing_qty',
        'packing_value',
        'shipment_qty',
        'shipment_value'
    ];

    protected $casts = [
        'fabric_req_qty' => Json::class,
        'fabric_booked_qty' => Json::class,
        'yarn_issue_qty' => Json::class,
        'finish_fab_qty' => Json::class
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'style_id')->withDefault();
    }
}
