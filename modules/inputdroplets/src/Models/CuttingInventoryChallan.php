<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Models;

use Carbon\Carbon;
use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Merchandising\Filters\Filter;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class CuttingInventoryChallan extends Model
{
    use FactoryIdTrait, SoftDeletes, CascadeSoftDeletes;

    protected $table = 'cutting_inventory_challans';
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
    protected $cascadeDeletes = ['cutting_inventory'];

    const APPROVAL_PAGE_NAME =  'Sewing Input Challan Approval(Cutting Manager)';

    public function line()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Line', 'line_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->withDefault();
    }

    public function factory()
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\Factory')->withDefault();
    }

    public function cutting_inventory()
    {
        return $this->hasMany(CuttingInventory::class, 'challan_no', 'challan_no');
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

    public static function getFirstInpurDate($purchaseOrderId, $colorId)
    {
        /*return self::where(['purchase_order_id' => $purchaseOrderId, 'color_id' => $colorId])
                ->first()->input_date ?? '';*/
        return self::where('color_id', $colorId)
                ->orderBy('input_date')
                ->first()->input_date ?? '';
    }

    public function scopeApprovalFilter($query, $request, $step)
    {
        $challan_date = $request->get('challan_date') ?? null;

        return $query->when($request->get('factory_id'), Filter::applyFilter('factory_id', $request->get('factory_id')))
            ->when($request->get('challan_no'), Filter::applyFilter('challan_no', $request->get('challan_no')))
            ->when($challan_date, function($query) use($challan_date) {
                $query->whereDate('updated_at', $challan_date);
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
    public function cutManager()
    {
        return $this->belongsTo(User::class, 'cut_manager_approved_id', 'id')->withDefault();
    }
}
