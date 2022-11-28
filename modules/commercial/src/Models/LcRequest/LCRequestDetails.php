<?php

namespace SkylarkSoft\GoRMG\Commercial\Models\LcRequest;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LCRequestDetails extends Model
{
    use HasFactory;

    protected $table = 'lc_request_details';
    protected $fillable = [
        'lc_request_id',
        'purchase_order_id',
        'style_name',
        'po_no',
        'customer',
        'description',
        'ship_mode',
        'po_quantity',
        'rate',
        'amount',
        'delivery_date',
        'co',
    ];
}
