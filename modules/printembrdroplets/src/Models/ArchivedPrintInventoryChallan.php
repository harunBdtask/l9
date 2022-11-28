<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;
use SkylarkSoft\GoRMG\SystemSettings\Models\PrintFactory;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class ArchivedPrintInventoryChallan extends Model
{
    use FactoryIdTrait, SoftDeletes, CascadeSoftDeletes;

    protected $table = 'archived_print_inventory_challans';

    protected $fillable = [
        'challan_no',
        'status',
        'bag',
        'operation_name',
        'part_id',
        'send_total_qty',
        'print_factory_id',
        'security_status',
        'factory_id',
        'security_staus',
        'cut_manager_approval_steps',
        'cut_manager_approval_status',
        'cut_manager_approved_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];
    protected $cascadeDeletes = ['print_inventory'];

    public function factory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory')->withDefault();
    }

    public function printFactory()
    {
        return $this->belongsTo(PrintFactory::class)->withDefault();
    }

    public function print_inventory()
    {
        return $this->hasMany(PrintInventory::class, 'challan_no', 'challan_no');
    }

    // print_inventory = print_inventories
    public function archived_print_inventory()
    {
        return $this->hasMany(ArchivedPrintInventory::class, 'challan_no', 'challan_no');
    }
    
    public function part()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Part')->withDefault();
    }     

    public function createdUser()
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }  

    public function cuttingManager()
    {
        return $this->belongsTo(User::class, 'cut_manager_approved_id')->withDefault();
    }

    public static function getPurchaseOrderWiseParties($purchaseOrderId)
    {
        $parties = self::withoutGlobalScopes()
            ->with('printFactory')
            ->leftJoin('print_inventories', 'print_inventories.challan_no', 'print_inventory_challans.challan_no')
            ->leftJoin('bundle_cards', 'bundle_cards.id', 'print_inventories.bundle_card_id')
            ->where('bundle_cards.purchase_order_id', $purchaseOrderId)
            ->where('print_inventory_challans.factory_id', factoryId())
            ->whereNull('print_inventory_challans.deleted_at')
            ->select('print_inventory_challans.print_factory_id')
            ->groupBy('print_inventory_challans.print_factory_id')->get();

        $partList = [];
        foreach ($parties as $party) {
            $partList[] = $party->printFactory->factory_name ?? '';
        }
        return $partList;
    }
}
