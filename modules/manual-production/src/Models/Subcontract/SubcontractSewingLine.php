<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class SubcontractSewingLine extends Model
{
    use FactoryIdTrait, SoftDeletes;

    protected $table = 'subcontract_sewing_lines';

    protected $fillable = [
        'subcontract_factory_profile_id',
        'subcontract_sewing_floor_id',
        'floor_name',
        'line_name',
        'responsible_person',
        'sorting',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'factory_id'
    ];

    protected $dates = ['deleted_at'];

    public function subContractFactoryProfile(): BelongsTo
    {
        return $this->belongsTo(SubcontractFactoryProfile::class, 'subcontract_factory_profile_id')->withDefault();
    }

    public function subContractSewingFloor(): BelongsTo
    {
        return $this->belongsTo(SubcontractSewingFloor::class, 'subcontract_sewing_floor_id')->withDefault();
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }
}
