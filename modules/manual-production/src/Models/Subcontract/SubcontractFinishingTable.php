<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class SubcontractFinishingTable extends Model
{
    use FactoryIdTrait, SoftDeletes;

    protected $table = 'subcontract_finishing_tables';

    protected $fillable = [
        'subcontract_factory_profile_id', 'subcontract_finishing_floor_id', 'table_name', 'sorting', 'responsible_person', 'status', 'factory_id', 'created_by', 'updated_by', 'deleted_by'
    ];

    protected $dates = ['deleted_at'];
   
    public function subContractFactoryProfile(): BelongsTo
    {
        return $this->belongsTo(SubcontractFactoryProfile::class, 'subcontract_factory_profile_id')->withDefault();
    }

    public function subContractFinishingFloor(): BelongsTo
    {
        return $this->belongsTo(SubcontractFinishingFloor::class, 'subcontract_finishing_floor_id')->withDefault();
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }
}
