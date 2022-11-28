<?php

namespace SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\DyeingMachine;

class DyeingBatchMachineAllocation extends Model
{
    use SoftDeletes;
    use CommonModelTrait;

    protected $table = 'dyeing_batch_machine_allocations';
    protected $primaryKey = 'id';
    protected $fillable = [
        'dyeing_batch_id',
        'machine_id',
        'distribution_qty',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function machine(): BelongsTo
    {
        return $this->belongsTo(DyeingMachine::class, 'machine_id', 'id')
            ->withDefault();
    }

    public function dyeingBatch(): BelongsTo
    {
        return $this->belongsTo(DyeingBatch::class, 'dyeing_batch_id', 'id')
            ->withDefault();
    }
}
