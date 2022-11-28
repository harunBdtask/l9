<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;

class DateFloorWisePrintEmbrReport extends Model
{
    use FactoryIdTrait;

    protected $table = "date_floor_wise_print_embr_reports";

    protected $fillable = [
        'production_date',
        'cutting_floor_id',
        'buyer_id',
        'order_id',
        'garments_item_id',
        'purchase_order_id',
        'color_id',
        'print_sent_qty',
        'print_received_qty',
        'print_rejection_qty',
        'embroidery_sent_qty',
        'embroidery_received_qty',
        'embroidery_rejection_qty',
        'factory_id',
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function cuttingFloor()
    {
        return $this->belongsTo(CuttingFloor::class, 'cutting_floor_id')->withDefault();
    }

    public function cuttingFloorWithoutGlobalScopes()
    {
        return $this->belongsTo(CuttingFloor::class, 'cutting_floor_id')->withoutGlobalScopes();
    }

    public function garmentsItem()
    {
        return $this->belongsTo(GarmentsItem::class, 'garments_item_id')->withDefault();
    }

    public function garmentsItemWithoutGlobalScopes()
    {
        return $this->belongsTo(GarmentsItem::class, 'garments_item_id')->withoutGlobalScopes();
    }

    public function buyer()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer', 'buyer_id')->withDefault();
    }

    public function buyerWithoutGlobalScopes()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Buyer', 'buyer_id')->withoutGlobalScopes();
    }

    public function order()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id')->withDefault();
    }

    public function orderWithoutGlobalScopes()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id')->withoutGlobalScopes();
    }

    public function purchaseOrder()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder', 'purchase_order_id')->withDefault();
    }

    public function purchaseOrderWithoutGlobalScopes()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder', 'purchase_order_id')->withoutGlobalScopes();
    }

    public function color()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Color', 'color_id')->withDefault();
    }

    public function colorWithoutGlobalScopes()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Color', 'color_id')->withoutGlobalScopes();
    }

    public function factory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory', 'factory_id');
    }

    public function factoryWithoutGlobalScopes()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory', 'factory_id')->withoutGlobalScopes();
    }

    public static function getPreviousQtys($data)
    {
        $queryData = self::query()
            ->selectRaw("SUM(print_sent_qty) as print_sent_qty, SUM(print_received_qty) as print_received_qty, SUM(print_rejection_qty) as print_rejection_qty, SUM(embroidery_sent_qty) as embroidery_sent_qty, SUM(embroidery_received_qty) as embroidery_received_qty, SUM(embroidery_rejection_qty) as embroidery_rejection_qty")
            ->whereDate('production_date', '<', $data['production_date'])
            ->where([
                'buyer_id' => $data['buyer_id'],
                'order_id' => $data['order_id'],
                'garments_item_id' => $data['garments_item_id'],
                'color_id' => $data['color_id'],
            ])
            ->groupBy([
                'buyer_id',
                'order_id',
                'garments_item_id',
                'color_id',
            ])->first();
        return [
            'print_sent_qty' =>($queryData && $queryData->print_sent_qty) ? $queryData->print_sent_qty : 0,
            'print_received_qty' =>($queryData && $queryData->print_received_qty) ? $queryData->print_received_qty : 0,
            'print_rejection_qty' =>($queryData && $queryData->print_rejection_qty) ? $queryData->print_rejection_qty : 0,
            'embroidery_sent_qty' =>($queryData && $queryData->embroidery_sent_qty) ? $queryData->embroidery_sent_qty : 0,
            'embroidery_received_qty' =>($queryData && $queryData->embroidery_received_qty) ? $queryData->embroidery_received_qty : 0,
            'embroidery_rejection_qty' =>($queryData && $queryData->embroidery_rejection_qty) ? $queryData->embroidery_rejection_qty : 0,
        ];
    }
}
