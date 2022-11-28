<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract;

use App\FactoryIdTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class SubcontractFinishingFloor extends Model
{
    use FactoryIdTrait, SoftDeletes, CascadeSoftDeletes;

    protected $table = 'subcontract_finishing_floors';

    protected $fillable = [
        'subcontract_factory_profile_id', 'floor_name', 'responsible_person', 'status', 'factory_id', 'created_by', 'updated_by', 'deleted_by'
    ];
    
    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [
        'subcontractFinishingTables'
    ];

    public function subContractFactoryProfile(): BelongsTo
    {
        return $this->belongsTo(SubcontractFactoryProfile::class, 'subcontract_factory_profile_id')->withDefault();
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function subcontractFinishingTables(): HasMany
    {
        return $this->hasMany(SubcontractFinishingTable::class, 'subcontract_finishing_floor_id', 'id');
    }
}
