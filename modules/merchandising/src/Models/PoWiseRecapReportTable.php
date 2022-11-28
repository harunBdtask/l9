<?php

namespace SkylarkSoft\GoRMG\Merchandising\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;

class PoWiseRecapReportTable extends Model
{
    use SoftDeletes;
    use FactoryIdTrait;

    protected $table = 'po_wise_recap_report_table';
    protected $fillable = [
        'id',
        'order_id',
        'purchase_id',
        'buyer',
        'booking_no',
        'order_style_no',
        'po_no',
        'fabrication',
        'fab_special',
        'gsm',
        'item_id',
        'item',
        't_shirt',
        'polo',
        'pant',
        'intimate',
        'others',
        'order_qty',
        'unit_price',
        'total_value',
        'cm_dozon',
        'shipment_date',
        'print',
        'emb',
        'fac', 'pp',
        'remarks',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function buyers()
    {
        return $this->belongsTo(Buyer::class, 'buyer');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function purchase()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_id');
    }

    public function item_data()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
