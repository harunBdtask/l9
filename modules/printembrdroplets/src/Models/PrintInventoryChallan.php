<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FactoryIdTrait;
use SkylarkSoft\GoRMG\SystemSettings\Models\PrintFactory;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\Merchandising\Filters\Filter;

class PrintInventoryChallan extends Model
{
    use FactoryIdTrait, SoftDeletes, CascadeSoftDeletes;

    protected $table = 'print_inventory_challans';

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
    protected $appends = [
        'operation_name_value',
        'challan_date',
    ];

    public function getOperationNameValueAttribute()
    {
        $operationName = '';
        if (\array_key_exists('operation_name', $this->attributes) && $this->attributes['operation_name'] && \array_key_exists($this->attributes['operation_name'], \OPERATION)) {
            $operationName = OPERATION[$this->attributes['operation_name']];
        }
        return $operationName;
    }

    public function getChallanDateAttribute()
    {
        $date = '';
        if (\array_key_exists('created_at', $this->attributes) && $this->attributes['created_at']) {
            $date = date('Y-m-d', strtotime($this->attributes['created_at']));
        }
        return $date;
    }

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

    public function scopeApprovalFilter($query, $request, $step)
    {
        $challanDate = $request->get('challan_date') ?? null;
        return $query->when($request->get('factory_id'), Filter::applyFilter('factory_id', $request->get('factory_id')))
            ->when($request->get('challan_no'), Filter::applyFilter('challan_no', $request->get('challan_no')))
            ->when($challanDate, function($query) use($challanDate) {
                $query->whereDate('created_at', $challanDate);
            })
            ->when($request->get('approval_type'), function ($query) use ($request, $step) {
                $query->when($request->get('approval_type') == 1, function ($query) use ($step) {
                    $query->where('cut_manager_approval_status', 0)
                        ->where('cut_manager_approval_steps', $step - 1);
                })->when($request->get('approval_type') == 2, function ($query) use ($step) {
                    $query->where('cut_manager_approval_status', 1)
                    ->where('cut_manager_approval_steps', $step);
                });
            });
    }
}
