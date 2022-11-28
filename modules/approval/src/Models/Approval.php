<?php

namespace SkylarkSoft\GoRMG\Approval\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class Approval extends Model
{
    use SoftDeletes;

    protected $table = 'approvals';

    protected $fillable = [
        'factory_id',
        'page_name',
        'user_id',
        'buyer_ids',
        'alternative_user_id',
        'priority', 'created_by',
        'updated_by', 'deleted_by',
    ];

    protected $appends = [
        'buyer_names',
    ];

    const APPROVED = 2;
    const UNAPPROVED = 1;

    const PO_APPROVAL = 'PO Approval';
    const ORDER_APPROVAL = 'Order Approval';
    const BUDGET_APPROVAL = 'Budget Approval';
    const FABRIC_APPROVAL = 'Fabric Approval';
    const SHORT_FABRIC_APPROVAL = 'Short Fabric Approval';
    const SHORT_TRIMS_APPROVAL = 'Short Trims Approval';
    const TRIMS_APPROVAL = 'Trims Approval';
    const YARN_PURCHASE_APPROVAL = 'Yarn Purchase Approval';
    const YARN_PURCHASE_REQUISITION = 'Yarn Purchase Requisition';
    const SERVICE_APPROVAL = 'Service Approval';
    const EMBELLISHMENT_APPROVAL = 'Embellishment Approval';
    const GATE_PASS_CHALLAN_APPROVAL = 'Gate Pass Challan Approval';
    const PRICE_QUOTATION = 'Price Quotation';
    const PRINT_SEND_CHALLAN_APPROVAL_CUT_MANAGER = 'Print Send Challan Approval(Cutting Manager)';
    const YARN_STORE_APPROVAL = 'Yarn Store Approval';
    const CUTTING_QTY_APPROVAL = 'Cutting Qty. Approval';
    const DYES_CHEMICAL_STORE_APPROVAL = 'Dyes Chemical Store Approval';
    const FABRIC_CONS_APPROVAL = 'Fabric Cons. Approval';

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        self::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }

    public function getBuyerNamesAttribute()
    {
        if (isset($this->buyer_ids)) {
            $buyerIds = explode(',', $this->buyer_ids);

            if (count($buyerIds) == Buyer::count()) {
                return 'All Buyers';
            }

            return Buyer::query()->whereIn('id', $buyerIds)->get(['id', 'name']);
        }

        return 'N/A';
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class)->withDefault([
            'factory_name' => 'N/A',
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function alternativeUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'alternative_user_id')->withDefault();
    }
}
