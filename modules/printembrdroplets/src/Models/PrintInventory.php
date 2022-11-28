<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;

class PrintInventory extends Model
{   
	use FactoryIdTrait, SoftDeletes;

    protected $table = 'print_inventories';

    protected $fillable = [
        'challan_no',
        'bundle_card_id',
        'factory_id',
        'status',
        'print_status',
        'type',
        'created_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    public function bundle_card()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard', 'bundle_card_id');
    }

    public function cuttingInventory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory', 'bundle_card_id', 'bundle_card_id');
    }

    public function printInventoryChallan()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Printembrdroplets\Models\printInventoryChallan', 'challan_no', 'challan_no');
    }

    public function factory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory', 'factory_id', 'id');
    }

    public static function getChallaWiseBundleQuery($challan_no)
    {
        return self::with([
            'bundle_card:id,bundle_no,size_wise_bundle_no,suffix,cutting_no,bundle_card_generation_detail_id,quantity,total_rejection,print_rejection,embroidary_rejection,buyer_id,order_id,purchase_order_id,color_id,size_id,lot_id',
            'bundle_card.details:id,is_manual',
            'bundle_card.order:id,style_name',
            'bundle_card.purchaseOrder:id,po_no,po_quantity',
            'bundle_card.color:id,name',
            'bundle_card.size:id,name',
            'bundle_card.lot:id,lot_no'
        ])
            ->where('challan_no', $challan_no)
            ->orderby('bundle_card_id')
            ->get();
    }
}
