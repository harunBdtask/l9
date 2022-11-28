<?php

namespace SkylarkSoft\GoRMG\Procurement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Procurement\Services\ProcurementPOService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class ProcurePurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'procure_purchase_orders';
    protected $fillable = [
        'po_number',
        'requisition_id',
        'supplier_id',
        'po_date',
        'created_by',
        'is_integrated',
    ];

    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if (! $model->id) {
                $model->po_number = ProcurementPOService::generateUniqueId();
            }
            $model->created_by = Auth::id();
        });
    }

    public function poDetails(): HasMany
    {
        return $this->hasMany(ProcurePurchaseOrderDetail::class, 'purchase_order_id');
    }

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(ProcurementRequisition::class, 'requisition_id')->withDefault();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withDefault();
    }
}
