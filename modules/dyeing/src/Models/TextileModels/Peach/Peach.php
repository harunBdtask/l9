<?php

namespace SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Peach;

use App\Models\BelongsToBuyer;
use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\DyeingMachine;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingUnit;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId\PeachService;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatch;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrder;

class Peach extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;
    use BelongsToBuyer;

    protected $table = 'peaches';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unique_id',
        'factory_id',
        'buyer_id',
        'entry_basis',
        'dyeing_batch_id',
        'dyeing_batch_no',
        'textile_order_id',
        'textile_order_no',
        'production_date',
        'before_dia',
        'before_gsm',
        'sub_dyeing_unit_id',
        'after_dia',
        'after_gsm',
        'shift_id',
        'drum_speed',
        'dyeing_machine_id',
        'roller_speed',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'entry_basis_value',
    ];

    const ENTRY_BASIS = [
        1 => 'Batch Basis',
        2 => 'Order Basis',
    ];

    public function getEntryBasisValueAttribute(): string
    {
        return self::ENTRY_BASIS[$this->attributes['entry_basis']];
    }

    public static function booted()
    {
        static::saving(function ($model) {
            if (!$model->id && in_array('created_by', $model->getFillable())) {
                $model->unique_id = PeachService::generateUniqueId();
            }
        });
    }

    /*------------------------------------------------ Start Relations -----------------------------------------------*/

    public function dyeingBatch(): BelongsTo
    {
        return $this->belongsTo(DyeingBatch::class, 'dyeing_batch_id', 'id')
            ->withDefault();
    }

    public function textileOrder(): BelongsTo
    {
        return $this->belongsTo(TextileOrder::class, 'textile_order_id', 'id')
            ->withDefault();
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id')
            ->withDefault();
    }

    public function peachDetails(): HasMany
    {
        return $this->hasMany(PeachDetail::class, 'dyeing_peach_id', 'id');
    }

    public function dyeingUnit(): BelongsTo
    {
        return $this->belongsTo(SubDyeingUnit::class, 'sub_dyeing_unit_id', 'id')
            ->withDefault();
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(DyeingMachine::class, 'machine_id')
            ->withDefault();
    }

    /*------------------------------------------------- End Relations ------------------------------------------------*/

}
