<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractCuttingFloor;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractCuttingTable;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractFactoryProfile;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingTable;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class ManualCuttingProduction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'manual_cutting_productions';

    protected $fillable = [
        'production_date',
        'source',
        'factory_id',
        'subcontract_factory_id',
        'buyer_id',
        'order_id',
        'garments_item_id',
        'purchase_order_id',
        'color_id',
        'size_id',
        'cutting_floor_id',
        'cutting_table_id',
        'sub_cutting_floor_id',
        'sub_cutting_table_id',
        'no_of_bundles',
        'production_qty',
        'rejection_qty',
        'produced_by',
        'reporting_hour',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            if (in_array('created_by', $model->getFillable())) {
                $model->created_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (in_array('updated_by', $model->getFillable())) {
                $model->updated_by = auth()->id();
            }
        });

        static::deleted(function ($model) {
            if (in_array('deleted_by', $model->getFillable())) {
                DB::table($model->table)->where('id', $model->id)
                    ->update([
                        'deleted_by' => auth()->id(),
                    ]);
            }
        });
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id')->withDefault();
    }
   
    public function subcontractFactory(): BelongsTo
    {
        return $this->belongsTo(SubcontractFactoryProfile::class, 'subcontract_factory_id', 'id')->withDefault();
    }
   
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id', 'id')->withDefault();
    }
   
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id')->withDefault();
    }
   
    public function garmentsItem(): BelongsTo
    {
        return $this->belongsTo(GarmentsItem::class, 'garments_item_id', 'id')->withDefault();
    }
   
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id')->withDefault();
    }
   
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id', 'id')->withDefault();
    }
   
    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class, 'size_id', 'id')->withDefault();
    }
   
    public function cuttingFloor(): BelongsTo
    {
        return $this->belongsTo(CuttingFloor::class, 'cutting_floor_id', 'id')->withDefault();
    }
   
    public function cuttingTable(): BelongsTo
    {
        return $this->belongsTo(CuttingTable::class, 'cutting_table_id', 'id')->withDefault();
    }
   
    public function subCuttingFloor(): BelongsTo
    {
        return $this->belongsTo(SubcontractCuttingFloor::class, 'sub_cutting_floor_id', 'id')->withDefault();
    }
   
    public function subCuttingTable(): BelongsTo
    {
        return $this->belongsTo(SubcontractCuttingTable::class, 'sub_cutting_table_id', 'id')->withDefault();
    }
}
