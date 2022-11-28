<?php

namespace SkylarkSoft\GoRMG\TQM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;

class TqmSewingDhuDetails extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tqm_sewing_dhu_id',
        'factory_id',
        'production_date',
        'floor_id',
        'line_id',
        'buyer_id',
        'order_id',
        'purchase_order_id',
        'tqm_defect_id',
        'hour_0',
        'hour_1',
        'hour_2',
        'hour_3',
        'hour_4',
        'hour_5',
        'hour_6',
        'hour_7',
        'hour_8',
        'hour_9',
        'hour_10',
        'hour_11',
        'hour_12',
        'hour_13',
        'hour_14',
        'hour_15',
        'hour_16',
        'hour_17',
        'hour_18',
        'hour_19',
        'hour_20',
        'hour_21',
        'hour_22',
        'hour_23',
        'total_defect',
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

    public function sewingLine(): BelongsTo
    {
        return $this->belongsTo(Line::class, 'line_id')->withDefault();
    }

    public function sewingFloor(): BelongsTo
    {
        return $this->belongsTo(Floor::class, 'floor_id')->withDefault();
    }

    public function tqmDefect(): BelongsTo
    {
        return $this->belongsTo(TqmDefect::class, 'tqm_defect_id')->withDefault();
    }
}
