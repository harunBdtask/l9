<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingPeach;

use App\Models\BelongsToFactory;
use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\DyeingMachine;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingUnit;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatch;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrder;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\UId\SubDyeingPeachService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;

class SubDyeingPeach extends Model
{
    use SoftDeletes;
    use CommonModelTrait;
    use BelongsToFactory;

    protected $table = 'sub_dyeing_peaches';
    protected $primaryKey = 'id';
    protected $fillable = [
        'peach_uid',
        'factory_id',
        'supplier_id',
        'entry_basis',
        'sub_dyeing_batch_id',
        'sub_dyeing_batch_no',
        'sub_textile_order_id',
        'sub_textile_order_no',
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
            if (! $model->id && in_array('created_by', $model->getFillable())) {
                $model->peach_uid = SubDyeingPeachService::generateUniqueId();
            }
        });
    }

    /*------------------------------------------------ Start Relations -----------------------------------------------*/

    public function subDyeingBatch(): BelongsTo
    {
        return $this->belongsTo(SubDyeingBatch::class, 'sub_dyeing_batch_id', 'id')
            ->withDefault();
    }

    public function subTextileOrder(): BelongsTo
    {
        return $this->belongsTo(SubTextileOrder::class, 'sub_textile_order_id', 'id')
            ->withDefault();
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id')->withDefault();
    }

    public function peachDetails(): HasMany
    {
        return $this->hasMany(SubDyeingPeachDetail::class, 'sub_dyeing_peach_id', 'id');
    }

    public function subDyeingUnit(): BelongsTo
    {
        return $this->belongsTo(SubDyeingUnit::class, 'sub_dyeing_unit_id', 'id')->withDefault();
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(DyeingMachine::class, 'dyeing_machine_id')->withDefault();
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'supplier_id')->withDefault();
    }

    /*------------------------------------------------- End Relations ------------------------------------------------*/
}
