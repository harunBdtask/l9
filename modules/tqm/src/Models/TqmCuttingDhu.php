<?php

namespace SkylarkSoft\GoRMG\TQM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingTable;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class TqmCuttingDhu extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'factory_id',
        'production_date',
        'cutting_floor_id',
        'cutting_table_id',
        'buyer_id',
        'order_id',
        'purchase_order_id',
        'checked',
        'qc_pass',
        'total_defect',
        'reject',
        'reason',
        'dhu_level',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        static::deleting(function ($model) {
            if (in_array('deleted_by', $model->getFillable())) {
                DB::table($model->table)->where('id', $model->id)
                    ->update([
                        'deleted_by' => userId(),
                    ]);
            }
        });
    }

    public function details(): HasMany
    {
        return $this->hasMany(TqmCuttingDhuDetails::class, 'tqm_cutting_dhu_id');
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id')->withDefault();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id')->withDefault();
    }

    public function cuttingTable(): BelongsTo
    {
        return $this->belongsTo(CuttingTable::class, 'cutting_table_id')->withDefault();
    }

    public function cuttingFloor(): BelongsTo
    {
        return $this->belongsTo(CuttingFloor::class, 'cutting_floor_id')->withDefault();
    }
}
