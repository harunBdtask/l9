<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;
use Carbon\Carbon;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class ArchivedCuttingInventoryChallan extends Model
{
    use FactoryIdTrait, SoftDeletes, CascadeSoftDeletes;

    protected $table = 'archived_cutting_inventory_challans';
    protected $fillable = [
        'challan_no',
        'status',
        'line_id',
        'type',
        'print_status',
        'input_date',
        'color_id',
        'total_rib_size',
        'rib_comments',
        'created_by',
        'updated_by',
        'deleted_by',
        'factory_id'
    ];

    protected $dates = ['deleted_at'];
    protected $cascadeDeletes = [];

    public function line()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Line', 'line_id');
    }

    public function user()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\User', 'created_by')->withDefault();
    }

    public function factory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory')->withDefault();
    }

    public function cutting_inventory()
    {
        return $this->hasMany(CuttingInventory::class, 'challan_no', 'challan_no');
    }

    public function archived_cutting_inventory()
    {
        return $this->hasMany(ArchivedCuttingInventory::class, 'challan_no', 'challan_no');
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function order()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\Order', 'order_id')->withDefault();
    }

    public function purchaseOrder()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder', 'purchase_order_id')->withDefault();
    }

    public function color()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Color', 'color_id')->withDefault();
    }

    public function sewing_ouput()
    {
        return $this->hasMany('SkylarkSoft\GoRMG\Sewingdroplets\Models\Sewingoutput', 'challan_no', 'challan_no');
    }

    public function archived_sewing_ouput()
    {
        return $this->hasMany('SkylarkSoft\GoRMG\Sewingdroplets\Models\ArchivedSewingoutput', 'challan_no', 'challan_no');
    }

    public static function getFirstInpurDate($purchaseOrderId, $colorId)
    {
        return self::where('color_id', $colorId)
                ->orderBy('input_date')
                ->first()->input_date ?? '';
    }
}
