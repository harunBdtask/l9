<?php

namespace SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess;

use App\Models\CommonModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\DyeingMachine;

class SubDyeingBatchMachineAllocation extends Model
{
    use SoftDeletes;
    use CommonModelTrait;

    protected $table = 'sub_dyeing_batch_machine_allocations';
    protected $primaryKey = 'id';
    protected $fillable = [
        'sub_dyeing_batch_id',
        'sub_dyeing_recipe_id',
        'machine_id',
        'distribution_qty',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function machine(): BelongsTo
    {
        return $this->belongsTo(DyeingMachine::class, 'machine_id', 'id')->withDefault();
    }

    public function subDyeingBatch(): BelongsTo
    {
        return $this->belongsTo(SubDyeingBatch::class, 'sub_dyeing_batch_id', 'id')
            ->withDefault();
    }
}
