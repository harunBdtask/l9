<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgramMachineDistribution;

class Machine extends Model
{
    use SoftDeletes;
    use FactoryIdTrait;

    protected $fillable = [
        'id',
        'knitting_floor_id',
        'machine_no',
        'machine_type',
        'machine_type_info',
        'machine_name',
        'machine_rpm',
        'machine_code',
        'machine_dia',
        'machine_gg',
        'machine_capacity',
        'status',
        'factory_id',
    ];
    protected $dates = ['deleted_at'];
    protected $cascadeDeletes = [
        'knitCards',
        'rolls',
    ];

    const KNITTING = 2;
    const STATUS = [
        1 => 'Running',
        2 => 'Blank',
        3 => 'Stop'
    ];

    public function dyeingProductionPlan()
    {
        return $this->hasMany('Skylarksoft\Dyeingdroplets\Models\DyeingProductionPlan')->orderBy('id', 'asc');
    }

    public function lastDyeingProductionPlan()
    {
        return $this->hasOne('Skylarksoft\Dyeingdroplets\Models\DyeingProductionPlan')
            ->orderBy('id', 'desc');
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id', 'id');
    }

    public function knittingFloor(): BelongsTo
    {
        return $this->belongsTo(KnittingFloor::class);
    }

    public function knitProgramMachineDistribute(): BelongsTo
    {
        return $this->belongsTo(KnittingProgramMachineDistribution::class, 'id', 'machine_id');
    }

    public function knitCards(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('Skylarksoft\Knittingdroplets\Models\KnitCard', 'machine_id', 'id');
    }

    public function rolls()
    {
        return $this->hasMany('Skylarksoft\Knittingdroplets\Models\Roll', 'knitting_machine_id', 'id');
    }
}
