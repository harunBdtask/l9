<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract;

use App\FactoryIdTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class SubcontractFactoryProfile extends Model
{
    use FactoryIdTrait, SoftDeletes, CascadeSoftDeletes;

    protected $table = 'subcontract_factory_profiles';

    const OPERATION_TYPE = [
        1 => 'Cutting Factory',
        2 => 'Embellishment Factory',
        3 => 'Sewing Factory',
        4 => 'Finishing Factory',
        5 => 'Inspection Factory',
    ];

    protected $fillable = [
        'operation_type', 'name', 'short_name', 'address', 'responsible_person', 'email', 'contact_no', 'remarks', 'created_by', 'updated_by', 'deleted_by', 'factory_id', 'status'
    ];

    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = [
        'subcontractCuttingFloors',
        'subcontractCuttingTables',
        'subcontractEmbellishmentFloors',
        'subcontractFinishingFloors',
        'subcontractFinishingTables',
        'subcontractSewingFloors',
        'subcontractSewingLines',
    ];

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id')->withDefault();
    }

    public function subcontractCuttingFloors(): HasMany
    {
        return $this->hasMany(SubcontractCuttingFloor::class, 'subcontract_factory_profile_id', 'id');
    }

    public function subcontractCuttingTables(): HasMany
    {
        return $this->hasMany(SubcontractCuttingTable::class, 'subcontract_factory_profile_id', 'id');
    }

    public function subcontractEmbellishmentFloors(): HasMany
    {
        return $this->hasMany(SubcontractEmbellishmentFloor::class, 'subcontract_factory_profile_id', 'id');
    }

    public function subcontractFinishingFloors(): HasMany
    {
        return $this->hasMany(SubcontractFinishingFloor::class, 'subcontract_factory_profile_id', 'id');
    }

    public function subcontractFinishingTables(): HasMany
    {
        return $this->hasMany(SubcontractFinishingTable::class, 'subcontract_factory_profile_id', 'id');
    }

    public function subcontractSewingFloors(): HasMany
    {
        return $this->hasMany(SubcontractSewingFloor::class, 'subcontract_factory_profile_id', 'id');
    }

    public function subcontractSewingLines(): HasMany
    {
        return $this->hasMany(SubcontractSewingLine::class, 'subcontract_factory_profile_id', 'id');
    }
}
