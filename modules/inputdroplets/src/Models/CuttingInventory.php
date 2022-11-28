<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;

class CuttingInventory extends Model
{
    use FactoryIdTrait, SoftDeletes;

    protected $table = 'cutting_inventories';

    protected $fillable = [
        'bundle_card_id',
        'challan_no',
        'factory_id',
        'status',
        'print_status',
        'created_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at']; 

    public function bundlecard()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard', 'bundle_card_id');
    }

    public function factory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory', 'factory_id');
    }

    public function cutting_inventory_challan()
    {
        return $this->belongsTo(CuttingInventoryChallan::class, 'challan_no', 'challan_no');
    }

    public function sewingoutput()
    {
        return $this->hasOne('SkylarkSoft\GoRMG\Sewingdroplets\Models\Sewingoutput', 'bundle_card_id', 'bundle_card_id');
    }

    public static function orderColorLineWiseTotalInputQty($order_id, $color_id, $line_id)
    {
        return self::selectRaw("
        SUM( COALESCE(bundle_cards.quantity, 0) - COALESCE(bundle_cards.total_rejection, 0)) - if(
            (SUM(COALESCE(bundle_cards.print_rejection, 0)) > SUM(COALESCE(bundle_cards.embroidary_rejection, 0))), SUM(COALESCE(bundle_cards.print_rejection, 0)), 
            SUM(COALESCE(bundle_cards.embroidary_rejection, 0))
        ) as input_qty
        ")->join('bundle_cards', 'bundle_cards.id', 'cutting_inventories.bundle_card_id')
            ->join('buyers', 'buyers.id', 'bundle_cards.buyer_id')
            ->join('orders', 'orders.id', 'bundle_cards.order_id')
            ->join('colors', 'colors.id', 'bundle_cards.color_id')
            ->join('sizes', 'sizes.id', 'bundle_cards.size_id')
            ->join('cutting_inventory_challans', 'cutting_inventory_challans.challan_no', 'cutting_inventories.challan_no')
            ->whereNull('cutting_inventories.deleted_at')
            ->whereNull('cutting_inventory_challans.deleted_at')
            ->whereNull('bundle_cards.deleted_at')
            ->whereNull('buyers.deleted_at')
            ->whereNull('orders.deleted_at')
            ->whereNull('sizes.deleted_at')
            ->where([
                'bundle_cards.status' => 1,
                'bundle_cards.order_id' => $order_id,
                'bundle_cards.color_id' => $color_id,
                'cutting_inventory_challans.line_id' => $line_id,
            ])->first();
    }

    public static function getChallanBundles($challanNo, $printStatus)
    {
        return self::where('challan_no', $challanNo)->with([
            'bundlecard.details:id,is_manual',
            'bundlecard.buyer:id,name',
            'bundlecard.order:id,style_name',
            'bundlecard.purchaseOrder:id,po_no',
            'bundlecard.color:id,name',
            'bundlecard.size:id,name',
            'bundlecard.lot:id,lot_no'
        ])
            ->when($printStatus != 0, function ($query) {
                return $query->where('print_status', '!=', 0);
            })
            ->when($printStatus == 0, function ($query) use ($printStatus) {
                return $query->where('print_status', $printStatus);
            })
            ->orderBy('updated_at', 'asc')
            ->get();
    }
}
